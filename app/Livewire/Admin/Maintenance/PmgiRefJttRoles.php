<?php

namespace App\Livewire\Admin\Maintenance;

use Livewire\Component;
use App\Models\RefJttRoles;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;

class PmgiRefJttRoles extends Component
{
    Use WithPagination;

    public $edits = false; 

    public $id = null;

    public $role_name;
    public $role_description;

    public $user;

    public function exportPDF()
    {
        $data = RefJttRoles::select(['id', 'role_name', 'role_description', 'updated_at', 'updated_by'])->get();

        // Generate PDF
        $pdf = Pdf::loadView('pdf.admin.maintenance.ref_jtt_roles', compact('data'))->setPaper('A4', 'landscape');

        // Stream the PDF to the browser or download it
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'penyelengaraan_peranan_JTT.pdf');       
    }        

    public function edit($id)
    {
        $data = RefJttRoles::where('id', $id)->first();

        $this->edits = true;
        $this->id    = $id;

        $this->role_name          = $data->role_name;
        $this->role_description   = $data->role_description;
    }

    public function update()
    {
        $this->validate([
            'role_description'  => 'required',
        ],
        [
            'role_description.required'  => 'Sila masukkan deskripsi peranan.',
        ]);

         RefJttRoles::where('id', $this->id)->update([
            'role_description'  => Str::squish(strtoupper($this->role_description)),
            'updated_at'        => \Carbon\Carbon::now('Asia/Kuala_Lumpur'),
            'updated_by'        => $this->user,                                    
        ]);
 
        $this->edits = false; // close modal only

        // Livewire v3 event (name + payload)
        $this->dispatch('swal', title: 'Berjaya', text: 'Deskripsi Peranan Berjaya Dikemas Kini.', icon: 'success');
        redirect()->route('maintenance.admin.ref_jtt_roles');      
    }

    public function close()
    {
        $this->edits = false;
        $this->resetValidation('role_description');
    }   
    
    public function mount()
    {
        $this->user = auth()->user()->USERID;
    }    
    
    public function render()
    {
        $data = RefJttRoles::paginate(15);
        
        return view('livewire.admin.maintenance.pmgi-ref-jtt-roles', compact('data'))->extends('layouts.main');
    }
}
