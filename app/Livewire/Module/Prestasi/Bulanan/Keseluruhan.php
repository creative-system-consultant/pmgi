<?php

namespace App\Livewire\Module\Prestasi\Bulanan;

use App\Models\RefEvalPctg;
use App\Models\SummMthOfficer;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class Keseluruhan extends Component
{
    public $role;
    public $state;
    public $branch;
    public $pydId;
    public $date;
    public $percentage;
    private $reportDate;

    public function mount()
    {
        $this->reportDate = Carbon::parse($this->date);
    }

    public function render()
    {
        if ($this->role != 'pyd') {
            $officerData = $this->getAdminData();
        } else {
            $officerData = $this->getBranchData();
        }

        // Group data
        $groupedData = $this->groupData($officerData);

        $this->percentage = RefEvalPctg::where('state_code', '01') // once da approve utk by negeri, tukar ni.. skrg pkai 01 sbb smua negeri sama value
                ->whereDate('effective_date', '<=', $this->date)
                ->orderBy('evaluation_id', 'ASC')
                ->get();

        return view('livewire.module.prestasi.bulanan.keseluruhan', [
            'groupedData' => $groupedData,
        ]);
    }

    private function getAdminData(): Collection
    {
        $query = SummMthOfficer::with(['branch', 'officerBranch', 'fmsBankOfficers'])
            ->whereDate('report_date', $this->reportDate->copy()->endOfMonth()->format('Y-m-d'));

        if ($this->state != '%') {
            $query->where('branch_state_code', $this->state);
        }

        if ($this->branch != '%%') {
            $query->where('acct_branch_code', $this->branch);
        }

        // Filter by specific staff if pydId is provided, otherwise show all staff in the branch/state
        if ($this->pydId) {
            $query->where('officer_id', $this->pydId);
        }

        return $query->orderBy('branch_state_code', 'asc')
            ->orderBy('cawangan', 'asc')
            ->orderBy('incl_pmgi_flag', 'asc')
            ->get();
    }

    private function getBranchData(): Collection
    {
        $userData = SummMthOfficer::whereOfficerId(auth()->user()->USERID)
            ->orderBy('report_date', 'desc')
            ->first();

        if ($userData) {
            $branch_code = $userData->officer_branch_code;
        } else {
            return collect();
        }

        return SummMthOfficer::whereAcctBranchCode($branch_code)
            ->whereOfficerId(auth()->user()->USERID) // PYD only sees their own data
            ->whereDate('report_date', $this->reportDate->copy()->endOfMonth()->format('Y-m-d'))
            ->orderBy('incl_pmgi_flag', 'asc')
            ->get();
    }

    private function groupData(Collection $officerData): Collection
    {
        return $officerData->groupBy('branch_state_code')->map(function ($stateData) {
            return $stateData->groupBy('acct_branch_code')->map(function ($branchData) {
                return $branchData->groupBy('officer_id')->map(function ($officerRecords) {
                    return $officerRecords->take(1);
                });
            });
        });
    }
}
