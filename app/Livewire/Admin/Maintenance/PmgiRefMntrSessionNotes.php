<?php

namespace App\Livewire\Admin\Maintenance;

use App\Models\Ref_Mntr_Session_Notes;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class pmgiRefMntrSessionNotes extends Component
{
    Use WithPagination;

    public $edits = false; 

    public $sesn_note_code;
    public $sesn_note_desc;

    public $code = null;
    public $user;

    public function edit($code)
    {               
        $data = Ref_Mntr_Session_Notes::where('sesn_note_code', $code)->first();

        $this->edits = true;
        $this->code = $code;

        $this->sesn_note_code = $data->sesn_note_code;
        $this->sesn_note_desc = $data->sesn_note_desc;        
    }

    public function update()
    {
        $this->validate([
            'sesn_note_desc' => 'required|unique:pmgi_ref_mntr_session_notes,sesn_note_desc',
        ],
        [
            'sesn_note_desc.required' => 'Sila masukkan deskripsi nota sesi',
            'sesn_note_desc.unique'   => 'Deskripsi nota sesi ini telah digunakan',
        ]);         

        Ref_Mntr_Session_Notes::where('sesn_note_code', $this->code)->update([
            'sesn_note_desc'   => Str::squish($this->sesn_note_desc),
            'updated_at'       => \Carbon\Carbon::now('Asia/Kuala_Lumpur'),
            'updated_by'       => $this->user,
        ]);

        $this->edits = false; // close modal only

        // Livewire v3 event (name + payload)
        $this->dispatch('swal', title: 'Berjaya', text: 'Deskripsi Nota Sesi Berjaya Dikemas Kini', icon: 'success');    
        redirect()->route('maintenance.monitor_session_notes');  
    }    

    public function close()
    {
        $this->edits = false;
        $this->resetValidation('sesn_note_desc');
    }   
    
    public function mount()
    {
        $this->user = auth()->user()->USERID;
    }    
    
    public function render()
    {
        $data = Ref_Mntr_Session_Notes::paginate(15);

        return view('livewire.admin.maintenance.pmgi-ref-mntr-session-notes', compact('data'))->extends('layouts.main');
    }
}
