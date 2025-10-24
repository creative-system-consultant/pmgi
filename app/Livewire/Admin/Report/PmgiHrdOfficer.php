<?php

namespace App\Livewire\Admin\Report;

use App\Models\HrdOfficer;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

class PmgiHrdOfficer extends Component
{
    public function exportPDF()
    {
        $data = HrdOfficer::select(['negeri', 'cawangan', 'no_pekerja', 'nama', 
                                    'no_kp', 'jawatan', 'status', 'resign_date', 
                                    'tarikh_cuti_dari', 'tarikh_cuti_hingga',
                                    'kod_cuti'
                                    ])
                             ->get();

        // Generate PDF
        $pdf = Pdf::loadView('pdf.admin.report.fms_hrd_officers', compact('data'))->setPaper('A4', 'landscape');

        // Stream the PDF to the browser or download it
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'laporan_pengawai_pembangunan_sumber_manusia.pdf');       
    }       

    public function render()
    {
        $report = HrdOfficer::paginate(15);

        return view('livewire..admin.report.pmgi-hrd-officer', compact('report'))->extends('layouts.main');
    }
}
