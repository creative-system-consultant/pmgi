<?php

namespace App\Livewire\Admin\ExceptionReport;

use App\Exports\LaporanCawanganDikecualikan;
use App\Models\ExclBranch;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class PmgiExclBranch extends Component
{
    use WithPagination;

    public function exportExcel()
    {
        return Excel::download(new LaporanCawanganDikecualikan, 'Laporan_Cawangan_Dikecualikan.xlsx');
    }        

    public function render()
    {
        $data = ExclBranch::orderBy('state_name')->orderBy('branch_name')->paginate(15);   

        return view('livewire.admin.exception-report.excl-branch', compact('data'))->extends('layouts.main');
    }
}