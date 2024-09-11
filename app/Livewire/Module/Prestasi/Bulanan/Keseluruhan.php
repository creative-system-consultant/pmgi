<?php

namespace App\Livewire\Module\Prestasi\Bulanan;

use App\Models\SummMthOfficer;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class Keseluruhan extends Component
{
    public $role;
    public $state;
    public $branch;
    public $date;
    private $reportDate;

    public function mount()
    {
        $this->reportDate = Carbon::parse($this->date);
    }

    public function render()
    {
        if ($this->role == 'admin') {
            $officerData = $this->getAdminData();
        } else {
            $officerData = $this->getBranchData();
        }

        // Group data
        $groupedData = $this->groupData($officerData);

        return view('livewire.module.prestasi.bulanan.keseluruhan', [
            'groupedData' => $groupedData,
        ]);
    }

    private function getAdminData(): Collection
    {
        $query = SummMthOfficer::with(['branch', 'officerBranch'])
            ->whereDate('report_date', $this->reportDate->copy()->endOfMonth()->format('Y-m-d'));

        if ($this->state != '%') {
            $query->where('branch_state_code', $this->state);
        }

        if ($this->branch != '%%') {
            $query->where('acct_branch_code', $this->branch);
        }

        return $query->orderBy('branch_state_code', 'asc')
            ->orderBy('cawangan', 'asc')
            ->orderBy('incl_pmgi_flag', 'asc')
            ->get();
    }

    private function getBranchData(): Collection
    {
        $branch_code = SummMthOfficer::whereOfficerId(auth()->user()->userid)
            ->orderBy('report_date', 'desc')
            ->first()->officer_branch_code;

        return SummMthOfficer::whereAcctBranchCode($branch_code)
            ->whereDate('report_date', $this->reportDate->format('Y-m-d'))
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
