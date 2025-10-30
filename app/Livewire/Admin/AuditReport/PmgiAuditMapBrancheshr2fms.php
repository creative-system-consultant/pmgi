<?php

namespace App\Livewire\Admin\AuditReport;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AuditMapBrancheshr2fms;

class PmgiAuditMapBrancheshr2fms extends Component
{
    use WithPagination;

    public $start_date;
    public $end_date;

    public function exportPDF()
    {
        $data = AuditMapBrancheshr2fms::select(['seq_no', 'fms_state_name', 'fms_branch_name','fms_branch_code', 'hr_state_name', 'hr_branch_name', 'hr_branch_code', 'update_ind', 'updated_at', 'updated_by'])
                ->when($this->start_date && $this->end_date, function ($query){
                    $query->whereDate('updated_at', '>=' , $this->start_date)
                          ->whereDate('updated_at', '<=' , $this->end_date);
                })        
                ->orderBy('updated_at')
                ->orderBy('seq_no')
                ->get();

        // Generate PDF
        $pdf = Pdf::loadView('pdf.admin.audit.map_branches_hr2fms', compact('data'))->setPaper('A4', 'landscape');

        // Stream the PDF to the browser or download it
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'laporan_audit_pemetaan_cawangan_hr_ke_fms_pada_'.Carbon::parse($this->start_date)->format('d-m-Y').'_hingga_'.Carbon::parse($this->end_date)->format('d-m-Y').'.pdf');       
    }        

    public function searchDate()
    {
        $this->start_date = trim((string) $this->start_date);        
        $this->end_date   = trim((string) $this->end_date);     
    }    

    public function resetSearch()
    {
        $this->start_date = '';        
        $this->end_date   = '';        
        $this->dispatch('refreshPage');           
    }    

    public function mount()
    {
        $dateStart = AuditMapBrancheshr2fms::min('updated_at');
        
        $this->start_date = date('Y-m-d', strtotime($dateStart));
        $this->end_date   = Carbon::now()->toDateString();
    }    

    public function render()
    {
        $data = AuditMapBrancheshr2fms::select(['seq_no', 'fms_state_name', 'fms_branch_name','fms_branch_code', 'hr_state_name', 'hr_branch_name', 'hr_branch_code', 'update_ind', 'updated_at', 'updated_by'])
                ->when($this->start_date && $this->end_date, function ($query){
                    $query->whereDate('updated_at', '>=' , $this->start_date)
                          ->whereDate('updated_at', '<=' , $this->end_date);
                })        
                ->orderBy('updated_at')
                ->orderBy('seq_no')
                ->paginate(15);

        return view('livewire.admin.audit-report.pmgi-audit-map-brancheshr2fms', compact('data'))->extends('layouts.main');
    }
}
