<?php

namespace App\Livewire\Admin\Maintenance;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use App\Models\Map_States_hr2fms;

class pmgiMapStateshr2fms extends Component
{
    Use WithPagination;
    
    public $edits = false; 

    public $state_name = null;
    public $hr_state_name;

    public $user;

    public function edit($state_name)
    {
        $data = Map_States_hr2fms::where('fms_state_name', $state_name)->first();

        $this->edits = true;

        $this->state_name      = $state_name;
        $this->hr_state_name   = $data->hr_state_name;
    }

    public function update()
    {
        $this->validate([
            'hr_state_name'  => 'required|regex:/^[\pL\s.]+$/u|unique:pmgi_map_states_hr2fms,hr_state_name',
        ],
        [
            'hr_state_name.required'  => 'Sila masukkan nama negeri dalam sistem HR',
            'hr_state_name.regex'     => 'Format medan nama negeri dalam sistem HR tidak sah',
            'hr_state_name.unique'    => 'Nama negeri dalam sistem HR ini telah digunakan',
        ]);

        Map_States_hr2fms::where('fms_state_name', $this->state_name)->update([
            'hr_state_name'  => Str::squish(strtoupper($this->hr_state_name)),            
        ]);
 
        $this->edits = false; // close modal only

        // Livewire v3 event (name + payload)
        $this->dispatch('swal', title: 'Berjaya', text: 'Nama Negeri Dalam Sistem HR Berjaya Dikemas Kini.', icon: 'success');
        redirect()->route('maintenance.map_state_hr2fms');      
    }

    public function close()
    {
        $this->edits = false;
        $this->resetValidation('hr_state_name');
    }

    public function mount()
    {
        $this->user = auth()->user()->USERID;
    }    

    public function render()
    {
        $data = Map_States_hr2fms::paginate(15);

        return view('livewire.admin.maintenance.pmgi-map-states-hr2fms', compact('data'))->extends('layouts.main');
    }
}
