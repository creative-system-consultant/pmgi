<?php

namespace App\Livewire\Admin\ExceptionReport;

use App\Exports\LaporanPengecualianCawangan;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ExcpMissingBranch;
use Maatwebsite\Excel\Facades\Excel;

class PmgiExcpMissingBranch extends Component
{
    use WithPagination;

    public function exportExcel()
    {
        return Excel::download(new LaporanPengecualianCawangan, 'Laporan_Pengecualian_Cawangan.xlsx');
    }    

    public function render()
    {
        $data = ExcpMissingBranch::orderBy('hr_state_name')->orderBy('hr_branch_name')->paginate(15);         

        return view('livewire.admin.exception-report.excp-missing-branch', compact('data'))->extends('layouts.main');
    }
}