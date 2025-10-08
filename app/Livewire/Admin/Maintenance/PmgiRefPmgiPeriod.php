<?php

namespace App\Livewire\Admin\Maintenance;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use App\Models\Ref_pmgi_Period;
use Illuminate\Validation\Rule;

class pmgiRefpmgiPeriod extends Component
{
    Use WithPagination;

    public $insert = false;

    public $effective_date;
    public $wait_period;
    public $pmgi_level;

    public $user;

    public function add()
    {
        $this->insert = true;

        $this->effective_date = \Carbon\Carbon::now('Asia/Kuala_Lumpur')->toDateString();
        $this->wait_period    = '';
        $this->pmgi_level     = '';
    }

    public function store()
    {
        $this->validate([
        'effective_date' => 'required|date_format:Y-m-d|after_or_equal:today',
        'wait_period'    => 'required|integer|between:0,99',
        'pmgi_level'     => [
                                'required',
                                Rule::unique('pmgi_ref_pmgi_period', 'pmgi_level') ->where(fn ($level) => $level->whereDate('effective_date', $this->effective_date)),
                            ]
        ],
        [
            'effective_date.required'       => 'Sila masukkan tarikh kuatkuasa',
            'wait_period.required'          => 'Sila masukkan tempoh menunggu',
            'pmgi_level.required'           => 'Sila pilih peringkat pmgi',
            'pmgi_level.unique'             => 'Peringkat pmgi ini telah digunakan',
            'effective_date.after_or_equal' => 'Tarikh kuatkuasa hendaklah pada hari ini atau pada tarikh akan datang',
            'wait_period.integer'           => 'Tempoh menunggu mestilah nombor bulat',
            'wait_period.between'           => 'Tempoh menunggu mestilah dari 0 hingga 99'
        ]); 

        Ref_pmgi_Period::create([
            'effective_date' => $this->effective_date,
            'wait_period'    => Str::squish($this->wait_period),
            'pmgi_level'     => $this->pmgi_level,
            'created_at'     => \Carbon\Carbon::now('Asia/Kuala_Lumpur'),
            'created_by'     => $this->user,
            'updated_at'     => NULL,
        ]);
        
        $this->insert = false; // close modal only

        // Livewire v3 event (name + payload)
        $this->dispatch('swal', title: 'Berjaya', text: 'Berjaya Tambah Tempoh pmgi.', icon: 'success');
        redirect()->route('maintenance.admin.ref_pmgi_period');
    }

    public function close()
    {
        $this->insert = false;
        
        $this->resetValidation('effective_date');
        $this->resetValidation('wait_period');
        $this->resetValidation('pmgi_level');
    }

    public function mount()
    {
        $this->user = auth()->user()->USERID;
    }    

    public function render()
    {
        $data = Ref_pmgi_Period::with('level')->paginate(15);

        return view('livewire.admin.maintenance.pmgi-ref-pmgi-period', compact('data'))->extends('layouts.main');
    }
}
