<?php

namespace App\Livewire\Module\Prestasi;

use App\Models\BankOfficer;
use App\Models\BnmStatecode;
use App\Models\Branch;
use App\Models\SettPymPmc;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Kumulatif extends Component
{
    public $pmgiSession = false;
    public $pmgiSessionId;
    public $pydId;
    public $fromReportDate;
    public $toReportDate;
    public $fromData;
    public $fromDataMthName;
    public $toData;
    public $toDataMthName;
    public $data;

    // input
    #[Validate('required', message: 'Negeri diperlukan.')]
    public $state;

    #[Validate('required', message: 'Cawangan diperlukan.')]
    public $branch;

    #[Validate('required', message: 'Nama @ No Pekerja diperlukan.')]
    public $searchTerm;

    #[Validate('required', message: 'Dari diperlukan.')]
    public $from;

    #[Validate('required', message: 'Hingga diperlukan.')]
    public $to;

    public function mount()
    {
        if ($this->pmgiSessionId) {
            $this->populateDataForSession(); // Call for PMGI session users
        } else {
            $this->populateData(); // Call for regular users
        }
    }

    protected function populateDataForSession()
    {
        if($this->pmgiSessionId) { // used in pmgi session ; pegawai dinilai/ pegawai menilai/ pegawai mudah cara page when in session
            $setting = SettPymPmc::whereSessionId($this->pmgiSessionId)->first();

            $this->pydId = $setting->pyd_id;
            $report_date = Carbon::parse($setting->report_date);
            $this->fromReportDate = $report_date->copy()->subMonthsNoOverflow(1)->endOfMonth()->format('Y-m-d');
            $this->toReportDate = $report_date->copy()->endOfMonth()->format('Y-m-d');
            $this->getData();
        }
    }

    protected function populateData()
    {
        $role = [];
        foreach(auth()->user()->roles as $roles) {
            $role[] = $roles->name;
        }

        if (in_array('PYD', $role)) {
            $this->pydId = auth()->user()->userid;

            // prod use this
            // $report_date = now();
            // uat pmgi 1
            // $report_date = Carbon::createFromFormat('d/m/Y', '31/01/2023')->format('Y-m-d');

            // uat pmgi 2
            $report_date = Carbon::createFromFormat('d/m/Y', '30/04/2023');

            $this->fromReportDate = $report_date->copy()->subMonth(1)->endOfMonth()->format('Y-m-d');
            $this->toReportDate = $report_date->copy()->endOfMonth()->format('Y-m-d');
            $this->getData();
        }
    }

    public function search()
    {
        $this->validate();

        $this->searchTerm = strtoupper($this->searchTerm);

        $this->pydId = BankOfficer::whereBranchCode($this->branch)
                            ->where(function($q) {
                                $q->where('officer_name', 'LIKE', '%' . $this->searchTerm . '%')
                                ->orWhere('staffno', 'LIKE', '%' . $this->searchTerm . '%');
                            })
                            ->value('officer_id');

        $this->fromReportDate = Carbon::parse($this->from)->endOfMonth()->format('Y-m-d');
        $this->toReportDate = Carbon::parse($this->to)->endOfMonth()->format('Y-m-d');

        $this->getData();
    }

    protected function getData()
    {
        if($this->pydId) { // filter out null oficer_id; if not it will fetch all null data in summ mth officer
            $this->data = DB::table('PMGI_SUMM_MTH_OFFICER')
                            ->where('officer_id', $this->pydId)
                            ->whereBetween('report_date', [$this->fromReportDate, $this->toReportDate])
                            ->orderBy('report_date', 'asc')
                            ->get();

            // Calculate month names for each entry in the retrieved data
            $this->data->each(function ($item) {
                $item->month_name = Carbon::parse($item->report_date)->translatedFormat('F Y');
            });
        }
    }

    public function render()
    {
        $stateSelection = BnmStatecode::whereNotIn('code', ['00', '15', '16', '99'])
                                        ->orderBy('code', 'ASC')
                                        ->get();

        if($this->state) {
            $branchSelection = Branch::whereNotIn('closeflag', [1])
                                    ->whereNotIn('state_code', ['00', '15', '16', '99'])
                                    ->whereStateCode($this->state)
                                    ->orderBy('branch_name', 'ASC')
                                    ->get();
        } else {
            $branchSelection = Branch::whereNotIn('closeflag', [1])
                                    ->whereNotIn('state_code', ['00', '15', '16', '99'])
                                    ->orderBy('branch_code', 'ASC')
                                    ->get();
        }

        return view('livewire.module.prestasi.kumulatif', [
            'stateSelection' => $stateSelection,
            'branchSelection' => $branchSelection,
            'datas' => $this->data,
        ])->extends('layouts.main');
    }
}
