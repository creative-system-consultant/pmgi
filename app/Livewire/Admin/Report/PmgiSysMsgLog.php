<?php

namespace App\Livewire\Admin\Report;

use App\Exports\LaporanLogMesejSistem;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\SysMsgLog;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class PmgiSysMsgLog extends Component
{
    use WithPagination;

    public $start_date;
    public $end_date;

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

    public function exportExcel()
    {
        return Excel::download(new LaporanLogMesejSistem($this->start_date, $this->end_date), 'Laporan_Log_Mesej_Sistem_From_'.$this->start_date.'_To_'.$this->end_date.'.xlsx');
    }    
    
    public function mount()
    {
        $this->start_date = Carbon::now()->subMonth()->toDateString();
        $this->end_date   = Carbon::now()->toDateString();
    }

    public function render()
    {
        $data = SysMsgLog::select([
            'seq_no','report_date','user_id','pgm_grp', 
            'pgm_sub_grp', 'err_proc', 'err_msg', 'err_no', 
            'err_severity', 'err_state', 'err_line', 'event_timestamp',
            'msg_type', 'debug_notes'
        ])
        ->when($this->start_date && $this->end_date, function ($query){
            $query->whereBetween('report_date', [$this->start_date, $this->end_date]);
        })
        ->orderBy('seq_no')
        ->paginate(15);            

        return view('livewire.admin.report.pmgi-sys-msg-log', compact('data'))->extends('layouts.main');
    }
}
