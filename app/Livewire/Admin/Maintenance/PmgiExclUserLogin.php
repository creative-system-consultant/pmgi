<?php

namespace App\Livewire\Admin\Maintenance;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\ExcludeUserLogin;
use Barryvdh\DomPDF\Facade\Pdf;

class PmgiExclUserLogin extends Component
{
    Use WithPagination;

    public $insert = false;
    public $edits = false; 
    public $id = null;
    
    public $userid;
    public $username;
    public $user;    

    public function exportPDF()
    {
        $data = ExcludeUserLogin::select(['userid', 'username'])->get();

        // Generate PDF
        $pdf = Pdf::loadView('pdf.admin.maintenance.excl_user_login', compact('data'))->setPaper('A4', 'landscape');

        // Stream the PDF to the browser or download it
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'penyelengaraan_pengecualian_log_masuk_pengguna.pdf');       
    }       

    public function add()
    {
        $this->insert = true;
        
        $this->userid   = '';
        $this->username = '';
    }

    public function store()
    {
        $this->validate([
            'userid'   => 'required|unique:pmgi_excl_user_login,userid',
            'username' => 'required',
        ],
        [
            'userid.required'   => 'Sila masukkan ID pengguna', 
            'username.required' => 'Sila masukkan name pengguna',
            'userid.unique'     => 'ID Pengguna sudah digunakan'
        ]);

        ExcludeUserLogin::create([
            'userid'   => Str::squish(strtoupper($this->userid)),
            'username' => Str::squish(strtoupper($this->username)),
        ]);
        
        $this->insert = false; // close modal only

        // Livewire v3 event (name + payload)
        $this->dispatch('swal', title: 'Berjaya', text: 'Berjaya Tambah Pengecualian Log Masuk Pengguna.', icon: 'success');
        redirect()->route('maintenance.admin.');
    }

    public function edit($id)
    {
        $data = ExcludeUserLogin::where('userid', $id)->first();

        $this->edits = true;
        $this->id = $id;
        
        $this->userid   = $data->userid;        
        $this->username = $data->username;
    }

    public function update()
    {
        $this->validate([
            'username' => 'required',
        ],
        [
            'username.required' => 'Sila masukkan nama pengguna',
        ]);

        ExcludeUserLogin::where('userid', $this->id)->update([
            'username'   => Str::squish(strtoupper($this->username)),
        ]);
 
        $this->edits = false; // close modal only

        // Livewire v3 event (name + payload)
        $this->dispatch('swal', title: 'Berjaya', text: 'Pengecualian Log Masuk Pengguna Berjaya Dikemas Kini.', icon: 'success');
        redirect()->route('maintenance.admin.excl_user_login');      
    }

    public function confirmDelete($id)
    {
        $data = ExcludeUserLogin::where('userid', $id)->first();
        
        $this->dispatch('swal:confirm', title: 'Adakah anda pasti mahu menghapuskan pengecualian log masuk pengguna ini?', icon: 'warning', label1:'ID Pengguna', label2:'Nama Pengguna', param1:$data->userid, param2:$data->username, key:'id', param:$id);
    }
    
    #[On('delete')]
    public function delete($id)
    {        
        ExcludeUserLogin::where('userid', $id)->delete();
        
        $this->dispatch('swal', title: 'Berjaya', text: 'Pengencualian Log Masuk Pengguna Ini Berjaya Dihapuskan.', icon: 'success');
        redirect()->route('maintenance.admin.excl_user_login');
    }

    public function close()
    {
        $this->insert = false;
        $this->edits = false;

        $this->resetValidation('userid');
        $this->resetValidation('username');
    }

    public function mount()
    {
        $this->user = auth()->user()->USERID;
    }    

    public function render()
    {
        $data = ExcludeUserLogin::paginate(15);
        return view('livewire.admin.maintenance.pmgi-excl-user-login', compact('data'))->extends('layouts.main');
    }
}
