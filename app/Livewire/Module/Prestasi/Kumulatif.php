<?php

namespace App\Livewire\Module\Prestasi;

use App\Models\BankOfficer;
use App\Models\BnmStatecode;
use App\Models\Branch;
use App\Models\RefEvalPctg;
use App\Models\SettPymPmc;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;
use WireUi\Traits\Actions;

class Kumulatif extends Component
{
    use Actions;
    
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
    public $percentage;

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
        }
    }

    protected function populateDataForSession()
    {
        if($this->pmgiSessionId) { // used in pmgi session ; pegawai dinilai/ pegawai menilai/ pegawai mudah cara page when in session
            $setting = SettPymPmc::whereSessionId($this->pmgiSessionId)->first();

            $this->pydId = $setting->pyd_id;
            $report_date = Carbon::parse($setting->report_date);
            $this->fromReportDate = $report_date->copy()->subMonthNoOverflow()->endOfMonth()->format('Y-m-d');
            $this->toReportDate = $report_date->copy()->endOfMonth()->format('Y-m-d');
            $this->getData();
        }
    }

    public function search()
    {
        $role = [];
        foreach(auth()->user()->roles as $roles) {
            $role[] = $roles->name;
        }

        if (in_array('PYD', $role)) {
            $this->pydId = auth()->user()->USERID;
            // For PYD role, only validate from and to dates
            $this->validate([
                'from' => 'required',
                'to' => 'required'
            ], [
                'from.required' => 'Dari diperlukan.',
                'to.required' => 'Hingga diperlukan.'
            ]);
        } else {
            // For other roles, validate all fields
            $this->validate();

            $this->searchTerm = strtoupper($this->searchTerm);

            $this->pydId = BankOfficer::whereBranchCode($this->branch)
                                        ->where(function($q) {
                                            $q->where('officer_name', 'LIKE', '%' . $this->searchTerm . '%')
                                            ->orWhere('staffno', 'LIKE', '%' . $this->searchTerm . '%');
                                        })
                                        ->where('fms_userstatus', 1)
                                        ->value('officer_id');
        }

        $this->fromReportDate = Carbon::parse($this->from)->endOfMonth()->format('Y-m-d');
        $this->toReportDate = Carbon::parse($this->to)->endOfMonth()->format('Y-m-d');

        $this->getData();
    }

    protected function getData()
    {
        if($this->pydId) {
            $this->data = DB::table('pmgi_summ_mth_officer')
                            ->where('officer_id', $this->pydId)
                            ->whereBetween('report_date', [$this->fromReportDate, $this->toReportDate])
                            ->whereNotIn('incl_pmgi_flag', ['G', 'H'])
                            ->orderBy('report_date', 'asc')
                            ->get();

            // Show dialog if no data found
            if ($this->data->count() === 0) {
                $this->dialog()->info(
                    $title = 'Tiada Data',
                    $description = 'Tiada data prestasi ditemui untuk tempoh yang dipilih.'
                );
                return;
            }
            // Calculate month names for each entry in the retrieved data
            $this->data->each(function ($item) {
                $item->month_name = Carbon::parse($item->report_date)->translatedFormat('F Y');
            });

            $this->percentage = RefEvalPctg::where('state_code', $this->data->first()->branch_state_code)
                ->whereDate('effective_date', '<=', $this->toReportDate)
                ->orderBy('evaluation_id', 'ASC')
                ->get();
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
                                    ->whereBranchType('BRN')
                                    ->whereHideflag(0)
                                    ->whereStateCode($this->state)
                                    ->orderBy('branch_name', 'ASC')
                                    ->get();
        } else {
            $branchSelection = Branch::whereNotIn('closeflag', [1])
                                    ->whereNotIn('state_code', ['00', '15', '16', '99'])
                                    ->whereBranchType('BRN')
                                    ->whereHideflag(0)
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
