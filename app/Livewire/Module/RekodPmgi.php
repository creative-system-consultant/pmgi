<?php

namespace App\Livewire\Module;

use App\Models\BankOfficer;
use App\Models\BnmStatecode;
use App\Models\Branch;
use App\Models\SessionInfo;
use App\Models\SessionPydInfo;
use App\Models\SessionPymInfo;
use App\Models\SettPymPmc;
use App\Services\HtmlToImageService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RekodPmgi extends Component
{
    private $htmlToImageService;

    public $detailsModal = false;
    public $searchTerm;
    public $pydId;
    public $allSession;
    public $sessionId;
    public $isAdmin = true;

    public function __construct()
    {
        $this->htmlToImageService = new HtmlToImageService();
    }

    public function mount()
    {
        // Initialize $allSession as an empty collection
        $this->allSession = new Collection();

        $this->populateData();
    }

    protected function populateData()
    {
        $role = [];
        foreach(auth()->user()->roles as $roles) {
            $role[] = $roles->name;
        }

        if (in_array('PYD', $role)) {
            $this->pydId = auth()->user()->userid;
            $this->isAdmin = false;
            $this->getData();
        }
    }

    public function search()
    {
        $this->pydId = BankOfficer::where(function($q) {
                                $q->where('officer_name', 'LIKE', '%' . $this->searchTerm . '%')
                                ->orWhere('staffno', 'LIKE', '%' . $this->searchTerm . '%');
                            })
                            ->value('officer_id');

        $this->getData();
    }

    protected function getData()
    {
        $this->allSession = SettPymPmc::wherePydId($this->pydId)
                                        ->whereStatus(1)
                                        ->get();
    }

    public function toggleDetail($sessionId)
    {
        $this->sessionId = str_replace('/', '-', $sessionId);
        // $this->getDataFirstPage();

        $this->detailsModal = true;
    }

    public function streamRekodPmgi($sessionId)
    {
        $sessionId = str_replace('-', '/', $sessionId);

        $settInfo = SettPymPmc::with('mntrSession')->where('session_id', $sessionId)->first();
        $bankOfficerPyd = BankOfficer::whereOfficerId($settInfo->pyd_id)->first();
        $state = BnmStatecode::whereCode(substr($settInfo->branch_code, 0, 2))->value('description');
        $branch = Branch::where('branch_code', $settInfo->branch_code)->value('branch_name');
        $sessionInfo = SessionInfo::whereSessionId($sessionId)->first();
        $bankOfficerPym = BankOfficer::whereOfficerId($settInfo->pym_id)->first();
        $pydInfo = SessionPydInfo::with('problemTable')->whereSessionId($sessionId)->first();
        $pymInfo = SessionPymInfo::whereSessionId($sessionId)->first();
        $from = strtoupper(Carbon::parse($settInfo->report_date)->copy()->addMonth()->translatedFormat('F Y'));
        $to = strtoupper(Carbon::parse($settInfo->report_date)->copy()->addMonth(2)->translatedFormat('F Y'));

        // prestasi kumulatf var
        $report_date = Carbon::parse($settInfo->report_date);
        $fromReportDate = $report_date->copy()->subMonth(2)->endOfMonth()->format('Y-m-d');
        $toReportDate = $report_date->copy()->subMonth()->endOfMonth()->format('Y-m-d');

        $data = DB::table('PMGI_NAZ_SUMM_MTH_OFFICER')
                    ->where('officer_id', $settInfo->pyd_id)
                    ->whereBetween('report_date', [$fromReportDate, $toReportDate])
                    ->orderBy('report_date', 'asc')
                    ->get();

        // Calculate month names for each entry in the retrieved data
        $data->each(function ($item) {
            $item->month_name = Carbon::parse($item->report_date)->translatedFormat('F Y');
        });

        $paths = $this->htmlToImageService->generate(
            'pdf.prestasi_kumulatif',
            [
                'datas' => $data,
            ],
            'pdf/prestasi_kumulatif/',
            "{$settInfo->pyd_id}_{$fromReportDate}_to_{$toReportDate}"
        );

        $pdf = Pdf::loadView('pdf.borang_jpoc_12', compact(
                'settInfo','bankOfficerPyd', 'state', 'branch', 'sessionInfo', 'bankOfficerPym',
                'pydInfo', 'pymInfo', 'from', 'to', 'paths'
            ))->setPaper('A4', 'portrait');

        // Use output buffering to ensure the file is streamed before cleanup
        ob_start();
        $output = $pdf->stream('borang_jpoc_12.pdf');
        ob_end_clean();

        // Clean up the temporary files after the PDF has been streamed
        if (file_exists($paths['html'])) {
            unlink($paths['html']);
        }

        if (file_exists($paths['image'])) {
            unlink($paths['image']);
        }

        return $output;
    }

    public function render()
    {
        return view('livewire.module.rekod-pmgi')->extends('layouts.main');
    }
}
