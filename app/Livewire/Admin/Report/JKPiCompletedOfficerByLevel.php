<?php

namespace App\Livewire\Admin\Report;

use Livewire\Component;
use App\Models\MntrSession;
use Illuminate\Support\Str;
use App\Models\BnmStatecode;
use App\Models\Branch;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;

class JKPiCompletedOfficerByLevel extends Component
{
    use WithPagination;

    public $report_date;
    public $branch_code;
    public $negeri;

    // Search variable
    public $search_state;
    public $search_branch;

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
        $this->report_date = trim((string) $this->report_date);

        $branch_name = Str::squish(strtoupper($this->branch_code));

        $branch_code = Branch::where('branch_name', 'like', "%{$branch_name}%")->value('branch_code');        
        
        $state_name = Str::squish(strtoupper($this->negeri));

        $state_code = BnmStatecode::where('description', 'like', "%{$state_name}%")->value('code');

        $this->search_branch = $branch_code;
        $this->search_state  = $state_code;

        $this->resetPage();        
    }

    public function resetSearch()
    {
        $this->report_date = '';        
        $this->branch_code = '';   
        $this->negeri      = '';

        $this->dispatch('refreshPage');           
    }    

    public function render()
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
                ->paginate(15);

        return view('livewire.admin.report.JKPi-completed-officer-by-level', compact('report'))->extends('layouts.main');
    }
}
