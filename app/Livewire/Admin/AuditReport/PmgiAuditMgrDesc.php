<?php

namespace App\Livewire\Admin\AuditReport;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\AuditRefMgrDesc;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\WithPagination;

class PmgiAuditMgrDesc extends Component
{
    use WithPagination;

    public $start_date;
    public $end_date;

    public function exportPDF()
    {
        $data = AuditRefMgrDesc::select(['seq_no', 'mgr_desc', 'update_ind', 'updated_at', 'updated_by'])
                ->when($this->start_date && $this->end_date, function ($query){
                    $query->whereDate('updated_at', '>=' , $this->start_date)
                          ->whereDate('updated_at', '<=' , $this->end_date);
                })        
                ->orderBy('updated_at')
                ->orderBy('seq_no')
                ->get();

        // Generate PDF
        $pdf = Pdf::loadView('pdf.admin.audit.mgr_desc', compact('data'))->setPaper('A4', 'landscape');

        // Stream the PDF to the browser or download it
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'laporan_audit_deskripsi_pengurus_pada_'.Carbon::parse($this->start_date)->format('d-m-Y').'_hingga_'.Carbon::parse($this->end_date)->format('d-m-Y').'.pdf');       
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
        $this->start_date = AuditRefMgrDesc::min('updated_at');
        $this->end_date   = Carbon::today()->toDateString();
    }    

    public function render()
    {
        $data = AuditRefMgrDesc::select(['seq_no', 'mgr_desc', 'update_ind', 'updated_at', 'updated_by'])
                ->when($this->start_date && $this->end_date, function ($query){
                    $query->whereDate('updated_at', '>=' , $this->start_date)
                          ->whereDate('updated_at', '<=' , $this->end_date);
                })        
                ->orderBy('updated_at')
                ->orderBy('seq_no')
                ->paginate(15);

        return view('livewire.admin.audit-report.pmgi-audit-mgr-desc', compact('data'))->extends('layouts.main');
    }
}
