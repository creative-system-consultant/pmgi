<?php

namespace App\Livewire\Admin\Report;

use App\Models\BankOfficer;
use App\Models\BnmStatecode;
use App\Models\Branch;
use App\Models\MntrSession;
use App\Models\Ref_pmgi_Level;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class RingkasanPmgiBatchNegeriCawangan extends Component
{
    use WithPagination;

    #[Validate('required|date|lte:report_start_date')]
    public string $report_start_date = '';

    #[Validate('required|date|gte:report_start_date')]
    public $report_end_date;
    public $negeri;
    public $branch_code;
    public $batch;
    public $peringkat;
    public $role;
    public $userId;
    public $perPage = 15;
    public $hasSearched = false;

    // Search variable
    public $search_state;
    public $search_branch;
    public $search_batch;

    public function mount()
    {
        $this->userId = auth()->user()->USERID;
        $this->role = reportUserRole();

        $anchorDate = Carbon::now();
        $this->report_start_date = $anchorDate->copy()->subMonthsNoOverflow(2)->startOfMonth()->toDateString();
        $this->report_end_date = $anchorDate->copy()->subMonthNoOverflow()->endOfMonth()->toDateString();
        $this->negeri = 'ALL';
        $this->branch_code = 'ALL';
        $this->peringkat = 'ALL';
        $this->batch = 'ALL';
    }

    public function updatedNegeri()
    {
        $this->reset('branch_code', 'batch');
    }

    public function loadReport(): array
    {
        $spName = "[dbo].[UP_PMGI_RPT_LP001]";

        // 1) Get raw PDO connection from Laravel
        $pdo = DB::connection()->getPdo();

        // 2) Prepare & execute your stored procedure
        $stmt = $pdo->prepare("EXEC {$spName} ?, ?, ?, ?, ?, ?");
        $stmt->execute([
            $this->report_start_date,
            $this->report_end_date,
            $this->negeri,
            $this->branch_code,
            $this->peringkat,
            $this->batch,
        ]);

        // 3) Collect rowsets that actually have columns
        $allRowsets = [];

        do {
            // Check if the current result set has at least one column
            $columnCount = $stmt->columnCount();

            if ($columnCount > 0) {
                // If it does, safely fetch
                $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                // Store non-empty rowsets
                if (!empty($rows)) {
                    $allRowsets[] = $rows;
                }
            }
            // If columnCount == 0, we skip fetchAll() to avoid the IMSSP error

        } while ($stmt->nextRowset()); 
        // Move to any subsequent result sets until none remain

        // 4) Decide which rowset is the "real" one
        //    (e.g. the last one, the first one, or you might combine them)
        return !empty($allRowsets) ? end($allRowsets) : [];
    }

    protected function paginateStoredProcedureResults(array $rows): LengthAwarePaginator
    {
        $pageName = 'page';
        $currentPage = $this->getPage($pageName);
        $total = count($rows);
        $items = collect(array_slice($rows, ($currentPage - 1) * $this->perPage, $this->perPage))
            ->map(fn (array $item) => (object) $item)
            ->all();

        return new LengthAwarePaginator(
            $items,
            $total,
            $this->perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => $pageName,
            ]
        );
    }

    public function updatedPage()
    {
        // Pagination state is handled by Livewire. Data is rebuilt in render().
    }

    public function exportPDF()
    {
        $report = MntrSession::distinct()->with(['bankOfficer','state', 'branch', 'level', 'result'])
                ->whereIn('pmgi_level', ['PM1', 'PM2', 'PM3', 'JT1', 'JT2', 'HRD'])
                ->when($this->report_date || $this->branch_code || $this->negeri, function ($query) {
                    // Apply the search conditions
                    $query->where(function($query) {
                        if ($this->report_date) {
                            $query->whereDate('report_date', $this->report_date);
                        }

                        if ($this->branch_code) {
                            $query->where('branch_code', $this->search_branch);
                        }
                        
                        if ($this->negeri) {
                            $query->where('state_code', $this->search_state);
                        }
                    });

                    // Apply whereIn for pmgi_level after the search filters
                    $query->whereIn('pmgi_level', ['PM1', 'PM2', 'PM3', 'JT1', 'JT2', 'HRD']);
                })
                ->orderBy('report_date')
                ->orderBy('pmgi_level')
                ->get();

        // Generate PDF
        $pdf = Pdf::loadView('pdf.admin.report.JKPi_completed_officer_ByLevel', compact('report'))->setPaper('A4', 'landscape');

        // Stream the PDF to the browser or download it
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();    
        }, 'laporan_senarai_pegawai_selesai_JKPi_mengikut_peringkat.pdf');
    }
    
    public function searchFilter()
    {
        $this->report_start_date = trim((string) $this->report_start_date);
        $this->report_end_date = trim((string) $this->report_end_date);
        
        $state_name = Str::squish(strtoupper((string) ($this->negeri ?? '')));

        $state_code = BnmStatecode::whereLike('description', "%{$state_name}%")->value('code');

        $branch_name = Str::squish(strtoupper($this->branch_code));

        $branch_code = Branch::whereLike('branch_name', "%{$branch_name}%")->value('branch_code');

        // $batch_name = Str::squish(strtoupper($this->batch));

        // $batch_code = Batch::where('batch_name', 'like', "%{$batch_name}%")->value('batch_code');

        $this->search_branch = $branch_code;
        $this->search_state  = $state_code;
        // $this->search_batch  = $batch_code;

        $this->hasSearched = true;
        $this->resetPage();
    }

    public function resetSearch()
    {
        $this->report_start_date = '';
        $this->report_end_date = '';
        $this->negeri      = '';
        $this->branch_code = '';
        $this->peringkat      = '';
        $this->batch      = '';
        $this->hasSearched = false;

        $this->resetPage();
        $this->dispatch('refreshPage');
    }    

    public function render()
    {
        $pmgiLevel = Ref_pmgi_Level::query()
                    ->select('pmgi_level as level', 'pmgi_level_desc_short as description')
                    ->whereNotLike('pmgi_level', 'MN%')
                    ->get();

        // Add 'SEMUA PERINGKAT' to the beginning as an object
        $pmgiLevel->prepend((object) [
            'level' => "ALL",
            'description' => 'SEMUA PERINGKAT',
        ]);

        $stateSelection = BnmStatecode::select('code', 'description')
            ->whereNotIn('code', ['00', '15', '16', '99'])
            ->orderBy('code', 'ASC')
            ->get();

        // Add 'SEMUA NEGERI' to the beginning as an object
        $stateSelection->prepend((object) [
            'code' => "ALL",
            'description' => 'SEMUA NEGERI',
        ]);
        
        if($this->role == 'user')
        {
            $userState = BankOfficer::query()->whereOfficerId($this->userId)->first()?->fms_branch_state_code;
            $this->negeri = $this->role == 'user' ? $userState : $this->negeri;
        }

        // Handle branch selection based on the selected negeri
        if ($this->negeri && $this->negeri == "ALL") {
            // 'SEMUA NEGERI' selected
            $branchSelection = collect([
                (object) [
                    'branch_code' => 'ALL',
                    'branch_name' => 'SEMUA CAWANGAN',
                ],
            ]);
        } elseif ($this->negeri && $this->negeri != "ALL") {
            // A specific state is selected
            $branchSelection = Branch::select('branch_name', 'branch_code')
                ->whereNotIn('closeflag', [1])
                ->whereBranchType('BRN')
                ->whereHideflag(0)
                ->whereStateCode($this->negeri) // Use state code from selection
                ->orderBy('branch_name', 'ASC')
                ->get();

            // Add 'SEMUA CAWANGAN' to the beginning
            $branchSelection->prepend((object) [
                'branch_code' => 'ALL',
                'branch_name' => 'SEMUA CAWANGAN',
            ]);
        } else {
            // Default empty collection if no state is selected
            $branchSelection = collect();
        }

        $rows = $this->hasSearched ? $this->loadReport() : [];
        $results = $this->paginateStoredProcedureResults($rows);

        return view('livewire.admin.report.ringkasan-pmgi-batch-negeri-cawangan', compact('results', 'stateSelection', 'branchSelection', 'pmgiLevel'))->extends('layouts.main');
    }
}
