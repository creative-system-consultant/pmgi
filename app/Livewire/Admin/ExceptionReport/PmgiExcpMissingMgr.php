<?php

namespace App\Livewire\Admin\ExceptionReport;

use App\Exports\LaporanPengecualianPengawai;
use Livewire\Component;
use App\Models\ExcpMissingMgr;
use Carbon\Carbon;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class PmgiExcpMissingMgr extends Component
{
    use WithPagination;

    public $report_date;
    
    // Initialize an array to store last days of each month
    public $lastDaysOfMonths = [];    

    public function searchDate()
    {
        $this->report_date = trim((string) $this->report_date);

        // Validate that the selected date is one of the last days of the month
        $validateLastDays = $this->lastDaysOfMonths;

        if (!in_array($this->report_date, $validateLastDays)) {
            // Dispatch the SweetAlert message event to trigger the alert
            $this->dispatch('swal', title:'Tarikh laporan mestilah tarikh akhir dalam bulan tersebut', icon:'error');
            $this->dispatch('refreshPage');
        }        
    }

    public function resetSearch()
    {
        $this->report_date = '';              
        return redirect()->route('exceptionReport.admin.excp_missing_mgr');           
    }    

    public function mount()
    {
        $maxReportDate = ExcpMissingMgr::max('report_date');
        $data          = ExcpMissingMgr::select('report_date')
                        ->where('report_date', $maxReportDate)
                        ->orderBy('negeri')
                        ->orderBy('branch_code')
                        ->first();
        
        $this->report_date = $data->report_date;

        $currentDate = Carbon::now('Asia/Kuala_Lumpur')->subMonths();

        // // Reset array
        $this->lastDaysOfMonths = [];

        // Loop through the previous year, current year, and next year for both year and month
        for ($i = -26; $i <= 26; $i++) {
            // Calculate the adjusted year
            $year = $currentDate->copy()->addYears($i)->year;
            
            // Loop through the previous month, current month, and next month
            for ($j = -1; $j <= 12; $j++) {
                // Calculate the adjusted month
                $month = $currentDate->copy()->addMonths($j);
    
                $lastDay = Carbon::create($year, $month->month, 1)->lastOfMonth()->toDateString();

                $this->lastDaysOfMonths[] = $lastDay;
            }
        }
    }

    public function exportExcel()
    {
        return Excel::download(new LaporanPengecualianPengawai($this->report_date), 'Laporan_Pengecualian_Pengawai_'.$this->report_date.'.xlsx');
    }

    public function render()
    {
        $lastDaysOfMonths = $this->lastDaysOfMonths;

        $data = ExcpMissingMgr::select(['negeri','branch_code','cawangan','report_date'])
        ->when($this->report_date, function ($query){
            $query->whereDate('report_date', $this->report_date);
        })
        ->orderBy('negeri')->orderBy('branch_code')
        ->paginate(15);        

        return view('livewire.admin.exception-report.excp-missing-mgr', compact('data','lastDaysOfMonths'))->extends('layouts.main');
    }
}
