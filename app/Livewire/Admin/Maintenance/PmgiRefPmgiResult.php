<?php

namespace App\Livewire\Admin\Maintenance;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use App\Models\Ref_pmgi_Result;
use Barryvdh\DomPDF\Facade\Pdf;

class pmgiRefpmgiResult extends Component
{
    Use WithPagination;

    public $edits = false; 

    public $pmgi_result;
    public $pmgi_result_desc;

    public $result = null;
    public $user;

    public function exportPDF()
    {
        $data = Ref_pmgi_Result::select(['pmgi_result','pmgi_result_desc', 'pmgi_sys_result_desc', 'updated_at', 'updated_by'])->get();

        // Generate PDF
        $pdf = Pdf::loadView('pdf.admin.maintenance.ref_pmgi_result', compact('data'))->setPaper('A4', 'landscape');

        // Stream the PDF to the browser or download it
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'penyelengaraan_deskripsi_keputusan_PMGi.pdf');       
    }


    public function edit($result)
    {    
        $data = Ref_pmgi_Result::where('pmgi_result', $result)->first();

        $this->edits = true;
        $this->result = $result;

        $this->pmgi_result      = $data->pmgi_result;
        $this->pmgi_result_desc = $data->pmgi_result_desc;        
    }

    public function update()
    {
        $this->validate([
            'pmgi_result_desc' => 'required|unique:pmgi_ref_pmgi_result,pmgi_result_desc',
        ],
        [
            'pmgi_result_desc.required' => 'Sila masukkan deskripsi keputusan PMGi',
            'pmgi_result_desc.unique'   => 'Deskripsi keputusan PMGi ini telah digunakan',
        ]);         

        Ref_pmgi_Result::where('pmgi_result', $this->result)->update([
            'pmgi_result_desc'   => Str::squish($this->pmgi_result_desc),
            'updated_at'         => \Carbon\Carbon::now('Asia/Kuala_Lumpur'),
            'updated_by'         => $this->user,
        ]);

        $this->edits = false; // close modal only

        // Livewire v3 event (name + payload)
        $this->dispatch('swal', title: 'Berjaya', text: 'Deskripsi Keputusan PMGi Berjaya Dikemas Kini', icon: 'success');    
        redirect()->route('maintenance.admin.ref_pmgi_result');  
    }    

    public function close()
    {
        $this->edits = false;
        $this->resetValidation('pmgi_result_desc');
    }    

    public function mount()
    {
        $this->user = auth()->user()->USERID;
    }    

    public function render()
    {
        $data = Ref_pmgi_Result::paginate(15);

        return view('livewire.admin.maintenance.pmgi-ref-pmgi-result', compact('data'))->extends('layouts.main');
    }
}
