<?php

namespace App\Livewire\Module\Lantikan\Evaluator;

use App\Exports\EvaluatorSessionList;
use App\Jobs\CleanupTemporaryFiles;
use App\Jobs\SendLantikanPymPmcEmail;
use App\Models\BankOfficer;
use App\Models\MntrSession;
use App\Models\SettPymPmc;
use App\Services\HtmlToImageService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use WireUi\Traits\Actions;

abstract class BasePmgi extends Component
{
    use Actions;

    private $htmlToImageService;

    public $stateCode;
    public $selectedDate;
    public $cardModal = false;
    public $pmgi;
    public $selection = [];
    public $selectedPym;
    public $selectedPmc;

    protected function rules()
    {
        return [
            'selectedPym' => 'required',
            'selectedPmc' => [
                Rule::requiredIf(function () {
                    return $this->getPmgiLevel() == 'PM3';
                }),
                'different:selectedPym',
            ],
        ];
    }

    protected function messages()
    {
        return [
            'selectedPym.required' => 'PYM diperlukan.',
            'selectedPmc.required' => 'PMC diperlukan bagi PMGi 3.',
            'selectedPmc.different' => 'PMC tidak boleh sama dengan PYM.',
        ];
    }

    protected $listeners = ['refreshPmgi' => 'updateReportDate'];

    public function __construct()
    {
        $this->htmlToImageService = new HtmlToImageService();
    }

    public function mount($month, $year)
    {
        $this->stateCode = auth()->user()->stateCode();
        $this->updateReportDate($month, $year);
    }

    public function updateReportDate($month, $year)
    {
        $this->selectedDate = Carbon::create($year, $month, 1)->startOfMonth();
        $this->reset('selection');
    }

    abstract protected function getPmgiLevel(): string;

    public function showSelection($pmgi)
    {
        if(count($this->selection) == 0) {
            $this->emptySelection();
        } else {
            $this->pmgi = $pmgi;
        $this->cardModal = true;
        }
    }

    private function emptySelection()
    {
        $this->dialog()->error(
            $title = 'Ralat!',
            $description = 'Sila buat pilihan PYD dahulu sebelum teruskan dengan pemilihan.'
        );
    }

    public function save()
    {
        $this->validate();

        $fileUrl = $this->generateExcelFile();
        $this->processSelections();

        $pymImagePath = $this->generateImageFromHtml('pym');
        $pmcImagePath = $this->selectedPmc ? $this->generateImageFromHtml('pmc') : null;

        $pymEmail = $this->getPymEmailAddress();
        $pmcEmail = $this->getPmcEmailAddress();

        $this->sendEmails(
            $pymEmail,
            $pmcEmail,
            $fileUrl,
            $pymImagePath['image'],
            $pymImagePath['html'],
            $pmcImagePath ? $pmcImagePath['image'] : null,
            $pmcImagePath ? $pmcImagePath['html'] : null,
        );

        $this->resetAfterSave();
    }

    private function generateExcelFile()
    {
        $pmgiValue = substr($this->getPmgiLevel(), -1);
        $filePath = 'exports/PYDLIST_' . $this->selectedPym . '_' . ($this->selectedPmc ? $this->selectedPmc : '') . '_' . now()->format('Ymd_His') . '.xlsx';
        Excel::store(new EvaluatorSessionList($pmgiValue, $this->selectedPym, $this->selectedPmc, $this->selection, $this->selectedDate), $filePath, 'public');
        return 'storage/' . $filePath;
    }

    private function processSelections()
    {
        foreach ($this->selection as $pyd) {
            $pydInfo = MntrSession::whereOfficerId($pyd)
                                    ->whereDate('SESSION_DATE_START', $this->selectedDate)
                                    ->first();

            $sessionId = $this->generateSessionId($pydInfo, $pyd);

            $existingRecord = SettPymPmc::where('session_id', $sessionId)->first();

            $data = $this->prepareData($sessionId, $pydInfo, $pyd, $existingRecord);

            if ($existingRecord) {
                $existingRecord->update($data);
            } else {
                SettPymPmc::create($data);
            }
        }
    }

    private function generateSessionId($pydInfo, $pyd)
    {
        $datePart = now()->format('Ym');
        $formattedDatePart = substr($datePart, 2, 2) . substr($datePart, 4, 2);
        return 'PGM' . substr($pydInfo->pmgi_level, -1) . $formattedDatePart . '/' . $pydInfo->pmgi_cycle . '/' . $pyd;
    }

    private function prepareData($sessionId, $pydInfo, $pyd, $existingRecord)
    {
        return [
            'session_id' => $sessionId,
            'pmgi_level' => $pydInfo->pmgi_level,
            'pyd_id' => $pyd,
            'pym_id' => $this->selectedPym,
            'pmc_id' => $this->selectedPmc,
            'created_by' => $existingRecord ? $existingRecord->created_by : auth()->user()->userid,
            'created_at' => $existingRecord ? $existingRecord->created_at : now(),
            'updated_by' => auth()->user()->userid,
            'updated_at' => now(),
            'report_date' => $pydInfo->report_date,
            'branch_code' => $pydInfo->branch_code,
        ];
    }

    private function getPymEmailAddress()
    {
        return BankOfficer::whereOfficerId($this->selectedPym)->value('email');
    }

    private function getPmcEmailAddress()
    {
        return $this->selectedPmc ? BankOfficer::whereOfficerId($this->selectedPmc)->value('email') : null;
    }

    private function generateImageFromHtml($type)
    {
        $lastDate = Carbon::create($this->selectedDate->format('Y'), $this->selectedDate->format('m'), 20)->format('d-m-Y');
        $userId = $type === 'pym' ? $this->selectedPym : $this->selectedPmc;

        return $this->htmlToImageService->generate(
            'emails.lantikan_pym_pmc',
            [
                'type' => $type,
                'session' => substr($this->getPmgiLevel(), -1),
                'lastDate' => $lastDate,
            ],
            'emails/lantikan_pym_pmc/',
            "email_content_{$type}_{$userId}"
        );
    }

    private function sendEmails($pymEmail, $pmcEmail, $fileUrl, $pymImagePath, $pymHtmlPath, $pmcImagePath, $pmcHtmlPath)
    {
        $jobs = [];

        if ($pymEmail) {
            $jobs[] = new SendLantikanPymPmcEmail($pymEmail, $pmcEmail, $fileUrl, 'pym', $pymImagePath, $pymHtmlPath);
        }

        if ($pmcEmail) {
            $jobs[] = new SendLantikanPymPmcEmail($pymEmail, $pmcEmail, $fileUrl, 'pmc', $pmcImagePath, $pmcHtmlPath);
        }

        // Chain the cleanup job after the email jobs
        $jobs[] = new CleanupTemporaryFiles([$pymImagePath, $pmcImagePath], [$pymHtmlPath, $pmcHtmlPath], $fileUrl);

        // Dispatch the jobs as a chain
        Bus::chain($jobs)->dispatch();
    }

    private function resetAfterSave()
    {
        $this->cardModal = false;
        $this->reset('selection', 'selectedPym', 'selectedPmc');

        $this->dialog()->success(
            $title = 'Berjaya disimpan',
            $description = 'Lantikan PYM dan PMC berjaya disimpan.'
        );
    }

    public function render()
    {
        $data = DB::table('PMGI_NAZ_MNTR_SESSION as m')
            ->join('FMS_USERS as a', 'm.officer_id', '=', 'a.userid')
            ->join('BRANCHES as b', 'm.branch_code', '=', 'b.branch_code')
            ->join('PMGI_BANK_OFFICERS_NAZ as c', 'c.officer_id', '=', 'a.userid')
            ->select('a.userid', 'a.username', 'm.branch_code', 'c.officer_position', 'b.branch_name', 'm.pmgi_cycle', 'm.pmgi_level')
            ->whereDate('SESSION_DATE_START', $this->selectedDate)
            ->where('m.STATE_CODE', $this->stateCode)
            ->where('m.PMGI_LEVEL', $this->getPmgiLevel())
            ->whereNotIn('m.officer_id', function($query) {
                $query->select('d.PYD_ID')
                    ->from('PMGI_SETT_PYM_PMC as d')
                    ->whereDate('REPORT_DATE', $this->selectedDate->copy()->subMonth()->endOfMonth())
                    ->whereColumn('d.PYD_ID', 'm.officer_id');
            })
            ->orderBy('b.branch_name', 'asc')
            ->get();

        $pym = DB::table('FMS_USERS as a')
                        ->join('PMGI_BANK_OFFICERS_NAZ as b', 'b.officer_id', '=', 'a.userid')
                        ->join('BRANCHES as C', 'C.branch_code', '=', 'b.branch_code')
                        ->select('a.userid', 'b.officer_name', 'c.branch_name')
                        ->where('a.userstatus', 1)
                        ->where(DB::raw('substr(b.branch_code, 0, 2)'), $this->stateCode)
                        ->whereIn('b.officer_group', [5,12])
                        ->get()
                        ->toArray();

        $pmgiValue = substr($this->getPmgiLevel(), -1);

        return view('livewire.module.lantikan.evaluator.base-pmgi', [
            'pmgiValue' => $pmgiValue,
            'datas' => $data,
            'pym' => $pym,
        ]);
    }
}
