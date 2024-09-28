<?php

namespace App\Livewire\Module\Prestasi\Bulanan;

use App\Models\SummMthOfficer;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Ringkasan extends Component
{
    public $role;
    public $state;
    public $branch;
    public $date;
    public $reportDate;

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

        // Format the report_date field
        $officerData->transform(function ($item) {
            $item->report_date = Carbon::parse($item->report_date)->translatedFormat('F Y');
            return $item;
        });

        // Get unique months (limited to 2)
        $months = $officerData->pluck('report_date')->unique()->take(2)->values();

        // Group data
        $groupedData = $this->groupData($officerData);

        return view('livewire.module.prestasi.bulanan.ringkasan', [
            'groupedData' => $groupedData,
            'months' => $months,
        ]);
    }

    private function getAdminData(): Collection
    {
        $query = SummMthOfficer::with(['branch', 'officerBranch'])
            ->whereBetween('report_date', [$this->reportDate->copy()->subMonth()->startOfMonth(), $this->reportDate->copy()->endOfMonth()]);

        if ($this->state != '%') {
            $query->where('branch_state_code', $this->state);
        }

        if ($this->branch != '%%') {
            $query->where('acct_branch_code', $this->branch);
        }

        return $query->orderBy('report_date', 'asc')
                    ->orderBy('branch_state_code', 'asc')
                    ->orderBy('cawangan', 'asc')
                    ->orderBy('incl_pmgi_flag', 'asc')  // Ensure branch totals (N) come after individual records
                    ->get();
    }

    private function getBranchData(): Collection
    {
        $branch_code = SummMthOfficer::whereOfficerId(auth()->user()->userid)
            ->orderBy('report_date', 'desc')
            ->first()->officer_branch_code;

        return SummMthOfficer::whereAcctBranchCode($branch_code)
            ->whereBetween('report_date', [now()->subMonth()->startOfMonth(), now()->endOfMonth()])
            ->orderBy('report_date', 'asc')
            ->orderBy('incl_pmgi_flag', 'asc') // Ensure branch totals (N) come after individual records
            ->get();
    }

    private function groupData(Collection $officerData): Collection
    {
        return $officerData->groupBy('branch_state_code')->map(function ($stateData) {
            return $stateData->groupBy('acct_branch_code')->map(function ($branchData) {
                return $branchData->groupBy('officer_id')->map(function ($officerRecords) {
                    return $officerRecords->take(2);
                });
            });
        });
    }
}
