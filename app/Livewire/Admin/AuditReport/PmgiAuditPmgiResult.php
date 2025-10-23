<?php

namespace App\Livewire\Admin\AuditReport;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\AuditRefPmgiResult;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;

class PmgiAuditPmgiResult extends Component
{
    use WithPagination;

    public $start_date;
    public $end_date;

    public function exportPDF()
    {
        $data = AuditRefPmgiResult::select(['pmgi_result', 'pmgi_result_desc', 'update_ind', 'updated_at', 'updated_by'])
                ->when($this->start_date && $this->end_date, function ($query){
                    $query->whereDate('updated_at', '>=' , $this->start_date)
                          ->whereDate('updated_at', '<=' , $this->end_date);
                })        
                ->orderBy('updated_at')
                ->get();

        // Generate PDF
        $pdf = Pdf::loadView('pdf.admin.audit.pmgi_result', compact('data'))->setPaper('A4', 'landscape');

        // Stream the PDF to the browser or download it
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'laporan_audit_deskripsi_keputusan_pmgi_pada_'.Carbon::parse($this->start_date)->format('d-m-Y').'_hingga_'.Carbon::parse($this->end_date)->format('d-m-Y').'.pdf');       
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
        $dateStart = AuditRefPmgiResult::min('updated_at');
        
        $this->start_date = date('Y-m-d', strtotime($dateStart));
        $this->end_date   = Carbon::now()->toDateString();
    }    

    public function render()
    {


        $data = AuditRefPmgiResult::select(['pmgi_result', 'pmgi_result_desc', 'update_ind', 'updated_at', 'updated_by'])
                ->when($this->start_date && $this->end_date, function ($query){
                    $query->whereDate('updated_at', '>=' , $this->start_date)
                          ->whereDate('updated_at', '<=' , $this->end_date);
                })        
                ->orderBy('updated_at')
                ->paginate(15);
                
        return view('livewire.admin.audit-report.pmgi-audit-pmgi-result', compact('data'))->extends('layouts.main');
    }
}
