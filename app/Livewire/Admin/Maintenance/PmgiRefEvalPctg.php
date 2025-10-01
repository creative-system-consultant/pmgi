<?php

namespace App\Livewire\Admin\Maintenance;

use App\Models\BnmStatecode;
use App\Models\RefEvalPctg;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class pmgiRefEvalPctg extends Component
{
    Use WithPagination;

    public $insert = false;
    public $edits = false; 

    public $id = null;

    public $effective_date;
    public $state_code;
    public $evaluation_id;
    public $evaluation_percentage;

    public $user;

    public function add()
    {
        $this->insert = true;

        $this->effective_date = \Carbon\Carbon::now('Asia/Kuala_Lumpur')->toDateString();
        $this->state_code  = '';
        $this->evaluation_id  = '';
        $this->evaluation_percentage   = '';             
    }

    public function store()
    {
        $this->validate([
            'effective_date'         => [
                                            'required', 'date_format:Y-m-d', 'after_or_equal:today'                                           
                                        ],
            'state_code'             => [                    
                                            'required',                                          
                                        ],
            // 'evaluation_id'          => [
            //                                 'required',
            //                                  Rule::unique('pmgi_ref_eval_pctg', 'evaluation_id') ->where(fn ($eval) => $eval->whereDate('effective_date', $this->effective_date)),                                        
            //                             ],
            'evaluation_percentage'  => 'required|numeric|between:0,100',
        ],
        [
            'effective_date.required'         => 'Sila masukkan tarikh kuatkuasa',
            'state_code.required'             => 'Sila pilih negeri',
            'evaluation_id.required'          => 'Sila pilih kriteria penilaian',
            'evaluation_percentage.required'  => 'Sila masukkan peratus penilaian',
            'evaluation_id.unique'            => 'Penilaian ini telah digunakan',
            'effective_date.after_or_equal'   => 'Tarikh kuatkuasa mestilah pada hari ini atau pada tarikh akan datang',
            'evaluation_percentage.between'   => 'Peratus penilaian mestilah dari 0 hingga 100'
        ]);
        
        RefEvalPctg::create([
            'effective_date'         => $this->effective_date,
            'state_code'             => Str::squish(str_pad($this->state_code, '2', '0', STR_PAD_LEFT)),
            // 'evaluation_id'          => $this->evaluation_id,
            'evaluation_percentage'  => Str::squish(number_format($this->evaluation_percentage, 2, '.')),
            'created_at'             => \Carbon\Carbon::now('Asia/Kuala_Lumpur'),
            'created_by'             => $this->user,
            'updated_at'             => NULL
        ]);
        
        $this->insert = false; // close modal only   

        // Livewire v3 event (name + payload)
        $this->dispatch('swal', title: 'Berjaya', text: 'Berjaya Tamba Peratusan Penilaian.', icon: 'success');
        redirect()->route('maintenance.admin.ref_eval_pctg');
    }

    public function edit($id)
    {
        $data = RefEvalPctg::where('id', $id)->first();

        $evaluation_labels = [
            1 => 'Kriteria 1 - Kutipan',
            2 => 'Kriteria 2 - Bilangan membayar',
            3 => 'Kriteria 3 - Lawatan Seliaan',
            4 =>'Kriteria 4 - Prestasi NPF (Kawalan)',
            5 =>'Kriteria 5 - Prestasi NPF (Pemulihan)',
        ];

        $this->edits = true;

        $this->id                     = $id;
        $this->effective_date         = $data->effective_date;
        $this->state_code             = $data->bnmState->description;
        // $this->evaluation_id          = $evaluation_labels[$data->evaluation_id] ?? $data->evaluation_id;
        $this->evaluation_percentage  = $data->evaluation_percentage;
    }

    public function update()
    {
        $this->validate([
            'evaluation_percentage'  => 'required|numeric|between:0,100',
        ],
        [
            'evaluation_percentage.required'  => 'Sila masukkan Peratus Penilaian',
            'evaluation_percentage.between'   => 'Peratus penilaian mestilah dari 0 hingga 100'
        ]);

        RefEvalPctg::where('id', $this->id)->update([
            'evaluation_percentage'  => Str::squish(number_format($this->evaluation_percentage, 2, '.')),
            'updated_at'             => \Carbon\Carbon::now('Asia/Kuala_Lumpur'),
            'updated_by'             => $this->user,
        ]);
 
        $this->edits = false; // close modal only

        // Livewire v3 event (name + payload)
        $this->dispatch('swal', title: 'Berjaya', text: 'Peratus Penilaian Berjaya Dikemas Kini.', icon: 'success');
        redirect()->route('maintenance.admin.ref_eval_pctg');      
    }

    public function close()
    {
        $this->insert = false;
        $this->edits = false;

        $this->resetValidation('effective_date');
        $this->resetValidation('state_code');
        $this->resetValidation('evaluation_id');        
        $this->resetValidation('evaluation_percentage');        
    }

    public function mount()
    {
        $this->user = auth()->user()->USERID;
    }    

    public function render()
    {
        $data   = RefEvalPctg::with('bnmState')->paginate(15);
        $states = BnmStatecode::select(['code', 'description'])->get();
        
        return view('livewire.admin.maintenance.pmgi-ref-eval-pctg', compact('data', 'states'))->extends('layouts.main');
    }
}
