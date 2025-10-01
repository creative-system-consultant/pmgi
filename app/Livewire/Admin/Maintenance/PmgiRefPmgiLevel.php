<?php

namespace App\Livewire\Admin\Maintenance;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use App\Models\Ref_pmgi_Level;

class pmgiRefpmgiLevel extends Component
{
    Use WithPagination;

    public $edits = false; 

    public $pmgi_level;
    public $pmgi_level_desc;
    
    public $seqno = null;
    public $user;

    public function edit($seqno)
    {        
        $data = Ref_pmgi_Level::where('seq_no', $seqno)->first();

        $this->edits = true;
        $this->seqno = $seqno;

        $this->pmgi_level      = $data->pmgi_level;
        $this->pmgi_level_desc = $data->pmgi_level_desc;        
    }

    public function update()
    {
        $this->validate([
            'pmgi_level_desc' => 'required|unique:pmgi_ref_pmgi_level,pmgi_level_desc',
        ],
        [
            'pmgi_level_desc.required' => 'Sila masukkan deskripsi peringkat PMGi',
            'pmgi_level_desc.unique'   => 'Deskripsi peringkat PMGi telah digunakan'
        ]);         

        Ref_pmgi_Level::where('seq_no', $this->seqno)->update([
            'pmgi_level_desc'   => Str::squish(strtoupper($this->pmgi_level_desc)),
            'updated_at'       => \Carbon\Carbon::now('Asia/Kuala_Lumpur'),
            'updated_by'       => $this->user,            
        ]);

        $this->edits = false; // close modal only

        // Livewire v3 event (name + payload)
        $this->dispatch('swal', title: 'Berjaya', text: 'Deskripsi peringkat PMGi Berjaya Dikemas Kini', icon: 'success');    
        redirect()->route('maintenance.admin.ref_pmgi_level');  
    }    

    public function close()
    {
        $this->edits = false;
        $this->resetValidation('pmgi_level_desc');
    } 
    
    public function mount()
    {
        $this->user = auth()->user()->USERID;
    }    

    public function render()
    {
        $data = Ref_pmgi_Level::paginate(15);

        return view('livewire.admin.maintenance.pmgi-ref-pmgi-level', compact('data'))->extends('layouts.main');
    }
}
