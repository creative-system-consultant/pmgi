<?php

namespace App\Livewire\Admin\Maintenance;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\Ref_Mgr_Desc;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class pmgiRefMgrDesc extends Component
{
    Use WithPagination;

    public $insert = false;
    public $edits = false; 
    public $mgr = null;
    public $mgr_desc;
    public $user ;

    public function add()
    {
        $this->insert = true;
        $this->mgr_desc = '';
    }

    public function store()
    {
        $this->validate([
            'mgr_desc' => 'required',
        ],
        [
            'mgr_desc.required' => 'Sila masukkan Deskripsi Pengurus'
        ]);

        Ref_Mgr_Desc::create([
            'seq_no'     => DB::table('pmgi_ref_mgr_desc')->max('seq_no') + 1,
            'mgr_desc'   => Str::squish(strtoupper($this->mgr_desc)),
            'created_at' => \Carbon\Carbon::now('Asia/Kuala_Lumpur'),
            'created_by' => $this->user,
            'updated_at' => NULL,
        ]);
        
        $this->insert = false; // close modal only

        // Livewire v3 event (name + payload)
        $this->dispatch('swal', title: 'Berjaya', text: 'Berjaya Tambah Deskripsi Pengurus.', icon: 'success');
        redirect()->route('maintenance.admin.ref_mgr_desc');
    }

    public function edit($mgr)
    {
        $data = Ref_Mgr_Desc::where('seq_no', $mgr)->first();

        $this->edits = true;
        $this->mgr = $mgr;
        $this->mgr_desc = $data->mgr_desc;        
    }

    public function update()
    {
        $this->validate([
            'mgr_desc' => 'required|unique:pmgi_ref_mgr_desc,mgr_desc',
        ],
        [
            'mgr_desc.required' => 'Sila masukkan deskripsi pengurus',
            'mgr_desc.unique'   => 'Deskripsi pengurus ini telah digunakan'
        ]);

        Ref_Mgr_Desc::where('seq_no', $this->mgr)->update([
            'mgr_desc'   => Str::squish(strtoupper($this->mgr_desc)),
            'updated_at' => \Carbon\Carbon::now('Asia/Kuala_Lumpur'),
            'updated_by' => $this->user,
        ]);
 
        $this->edits = false; // close modal only

        // Livewire v3 event (name + payload)
        $this->dispatch('swal', title: 'Berjaya', text: 'Deskripsi Pengurus Berjaya Dikemas Kini.', icon: 'success');
        redirect()->route('maintenance.admin.ref_mgr_desc');      
    }

    public function confirmDelete($mgr)
    {
        $data = Ref_Mgr_Desc::where('seq_no', $mgr)->first();
        
        $this->dispatch('swal:confirm', title: 'Adakah anda pasti mahu menghapuskan deskripsi pengurus ini?', icon: 'warning', label1:'Deskripsi Pengurus', param1:$data->mgr_desc, key:'mgr', param:$mgr);
    }
    
    #[On('delete')]
    public function delete($mgr)
    {        
        Ref_Mgr_Desc::where('seq_no', $mgr)->update([
            'deleted_at' => \Carbon\Carbon::now('Asia/Kuala_Lumpur'), 
            'deleted_by' => $this->user,
            'updated_at' => NULL
        ]);
        
        $this->dispatch('swal', title: 'Berjaya', text: 'Deskripsi Pengurus Ini Berjaya Dihapuskan.', icon: 'success');
        redirect()->route('maintenance.admin.ref_mgr_desc');
    }

    public function close()
    {
        $this->insert = false;
        $this->edits = false;

         $this->resetValidation('mgr_desc');
    }

    public function mount()
    {
        $this->user = auth()->user()->USERID;
    }    

    public function render()
    {
        $data = Ref_Mgr_Desc::query()->whereNull(['deleted_at', 'deleted_by'])->paginate(15);
        
        return view('livewire.admin.maintenance.pmgi-ref-mgr-desc', compact('data'))->extends('layouts.main');
    }
}
