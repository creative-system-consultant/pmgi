<?php

namespace App\Livewire\Admin\Report;

use App\Models\MntrSession;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;

class PerincianStatusPmgiPegawai extends Component
{
    use WithPagination;

    public $report_date_generated;
    
    #[Validate('required|date|lte:report_start_date')]
    public $report_start_date;

    #[Validate('required|date|gte:report_start_date')]
    public $report_end_date;
    public $negeri;
    public $branch_code;
    public $peringkat;
    public $role;
    public $userId;
    public $perPage = 15;

    public function mount()
    {
        $this->userId = auth()->user()->USERID;

        $anchorDate = Carbon::now();
        $this->report_date_generated = $anchorDate->toDateString();

        // Use query params if coming from summary page, otherwise use defaults
        $this->report_start_date = request()->query('start', $anchorDate->copy()->subMonthsNoOverflow(2)->startOfMonth()->toDateString());
        $this->report_end_date = request()->query('end', $anchorDate->copy()->subMonthNoOverflow()->endOfMonth()->toDateString());
        $this->peringkat   = request()->query('peringkat', 'ALL');
        $this->branch_code = request()->query('branch', 'ALL');
        $this->negeri      = $this->branch_code !== 'ALL' ? substr($this->branch_code, 0, 2) : 'ALL';
    }

    public function loadReport(): array
    {
        $spName = "[dbo].[UP_PMGI_RPT_LP002A]";

        // 1) Get raw PDO connection from Laravel
        $pdo = DB::connection()->getPdo();

        // 2) Prepare & execute your stored procedure
        $stmt = $pdo->prepare("EXEC {$spName} ?, ?, ?, ?, ?");
        $stmt->execute([
            $this->report_start_date,
            $this->report_end_date,
            $this->negeri,
            $this->branch_code,
            $this->peringkat,
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

    public function render()
    {
        $rows = $this->loadReport();
        $results = $this->paginateStoredProcedureResults($rows);

        return view('livewire.admin.report.perincian-status-pmgi-pegawai', compact('results'))->extends('layouts.main');
    }
}
