<?php

namespace App\Livewire\Admin\Report;

use App\Models\BankOfficer;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;

class PmgiFMSBankOfficers extends Component
{
    Use WithPagination;

    public function exportPDF()
    {
        $data = BankOfficer::select(['officer_id', 'officer_name', 'nokp', 'branch_code', 
                                    'staffno', 'fms_userstatus', 'officer_position',
                                    'hr_mgr_flag', 'hr_date_resign'
                                    ])
                             ->get();

        // Generate PDF
        $pdf = Pdf::loadView('pdf.admin.report.fms_bank_officers', compact('data'))->setPaper('A4', 'landscape');

        // Stream the PDF to the browser or download it
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'laporan_pengawai_bank_FMS.pdf');       
    }        


    public function render()
    {
        $data = BankOfficer::paginate(15);

        return view('livewire.admin.report.pmgi-fms-bank-officers', compact('data'))->extends('layouts.main');
    }
}
