<?php

namespace App\Livewire\Admin\Maintenance;

use App\Models\BnmStatecode;
use App\Models\ExclBranch;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PmgiExclBranch extends Component
{
    Use WithPagination;

    public $modalExclude = false;
    public $action = "insert";
    public $state_code;
    public $branch_name;
    public $selectId;
    public $user;

    public function mount()
    {
        $this->user = auth()->user()->USERID;
    }

    public function add()
    {
        $this->action = "insert";
        $this->modalExclude = true;
        $this->state_code = '';
        $this->branch_name = '';
    }

    public function store()
    {
        $this->validate([
            'state_code' => 'required',
            'branch_name' => 'required',
        ],
        [
            '*.required' => 'Ruangan ini diperlukan'
        ]);

        $state_name = BnmStatecode::query()->where('code', $this->state_code)->first('description');

        ExclBranch::create([
            'state_code'    => $this->state_code,
            'state_name'    => trim($state_name->description),
            'branch_name'   => Str::squish(strtoupper($this->branch_name)),
            'created_at'    => now(),
            'created_by'    => $this->user,
            'updated_at'    => NULL,
        ]);
        
        $this->modalExclude = false; // close modal only

        // Livewire v3 event (name + payload)
        $this->dispatch('swal', title: 'Berjaya', text: 'Berjaya Tambah Pengecualian Cawangan.', icon: 'success');
        redirect()->route('maintenance.admin.maintenance_excl_branch');
    }

    public function edit($id)
    {
        $data = ExclBranch::where('id', $id)->first();

        $this->action = "edit";
        $this->modalExclude = true;
        $this->selectId = $id;
        $this->state_code = $data->state_code;
        $this->branch_name = $data->branch_name;
    }

    public function update()
    {
        $this->validate([
            'branch_name' => [
                'required',
                Rule::unique('pmgi_excl_branch', 'branch_name') ->where(fn ($branch_name) => $branch_name->where('state_code', $this->state_code))
            ],
        ],
        [
            'branch_name.required' => 'Nama Cawangan diperlukan',
            'branch_name.unique'   => 'Nama Cawangan ini telah digunakan bagi negeri ini'
        ]);

        ExclBranch::where('id', $this->selectId)->update([
            'state_code'   => $this->state_code,
            'branch_name'  => Str::squish(strtoupper($this->branch_name)),
            'updated_at'   => now(),
            'updated_by'   => $this->user,
        ]);

        $this->modalExclude = false; // close modal only

        // Livewire v3 event (name + payload)
        $this->dispatch('swal', title: 'Berjaya', text: 'Pengecualian Cawangan Berjaya Dikemaskini.', icon: 'success');
        redirect()->route('maintenance.admin.maintenance_excl_branch');      
    }

    public function confirmDelete($id)
    {
        $data = ExclBranch::where('id', $id)->first();
        
        $this->dispatch('swal:confirm', title: 'Adakah anda pasti mahu menghapuskan cawangan ini?', icon: 'warning', label1:'Cawangan', label2:'Negeri', param1:$data->branch_name, param2:$data->state_name, key:'id', param:$id);
    }
    
    #[On('delete')]
    public function delete($id)
    {        
        ExclBranch::query()->where('id', $id)->delete();
        
        $this->dispatch('swal', title: 'Berjaya', text: 'Cawangan Berjaya Dihapuskan.', icon: 'success');
        redirect()->route('maintenance.admin.maintenance_excl_branch');
    }

    public function close()
    {
        $this->modalExclude = false;

        $this->resetValidation(['state_code', 'branch_name']);
    }

    public function exportPDF()
    {
        $data = ExclBranch::select(['state_name','branch_name', 'created_at', 'created_by', 'updated_at', 'updated_by'])->orderBy('state_name')->orderBy('branch_name')->get();

        // Generate PDF
        $pdf = Pdf::loadView('pdf.admin.maintenance.pmgi_excl_branch', compact('data'))->setPaper('A4', 'landscape');

        // Stream the PDF to the browser or download it
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'penyelengaraan_pengecualian_cawangan.pdf');       
    }

    public function render()
    {
        $data = ExclBranch::orderBy('state_name')->orderBy('branch_name')->paginate(15);
        $states = BnmStatecode::query()->get();

        return view('livewire.admin.maintenance.pmgi-excl-branch', [
            'data' => $data,
            'states' => $states
        ])->extends('layouts.main');
    }
}
