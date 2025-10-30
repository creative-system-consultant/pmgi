<?php

namespace App\Livewire\Admin\AuditReport;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AuditMntrSession;
use App\Models\AuditRefPmgiLevel;

class PmgiAuditPmgiLevel extends Component
{
    use WithPagination;

    public $start_date;
    public $end_date;

    public function exportPDF()
    {
        $data = AuditRefPmgiLevel::select(['seq_no', 'pmgi_level', 'pmgi_level_desc', 'pmgi_sys_level_desc', 'update_ind', 'updated_at', 'updated_by'])
                ->when($this->start_date && $this->end_date, function ($query){
                    $query->whereDate('updated_at', '>=' , $this->start_date)
                          ->whereDate('updated_at', '<=' , $this->end_date);
                })        
                ->orderBy('updated_at')
                ->orderBy('seq_no')
                ->get();

        // Generate PDF
        $pdf = Pdf::loadView('pdf.admin.audit.pmgi_level', compact('data'))->setPaper('A4', 'landscape');

        // Stream the PDF to the browser or download it
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'laporan_audit_peringkat_pmgi_pada_'.Carbon::parse($this->start_date)->format('d-m-Y').'_hingga_'.Carbon::parse($this->end_date)->format('d-m-Y').'.pdf');       
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
        $dateStart = AuditRefPmgiLevel::min('updated_at');
        
        $this->start_date = date('Y-m-d', strtotime($dateStart));
        $this->end_date   = Carbon::now()->toDateString();
    }    

    public function render()
    {
        $data = AuditRefPmgiLevel::select(['seq_no', 'pmgi_level', 'pmgi_level_desc', 'pmgi_sys_level_desc', 'update_ind', 'updated_at', 'updated_by'])
                ->when($this->start_date && $this->end_date, function ($query){
                    $query->whereDate('updated_at', '>=' , $this->start_date)
                          ->whereDate('updated_at', '<=' , $this->end_date);
                })        
                ->orderBy('updated_at')
                ->orderBy('seq_no')
                ->paginate(15);

        return view('livewire.admin.audit-report.pmgi-audit-pmgi-level', compact('data'))->extends('layouts.main');
    }
}
