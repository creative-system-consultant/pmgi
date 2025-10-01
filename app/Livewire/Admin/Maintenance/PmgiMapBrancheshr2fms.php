<?php

namespace App\Livewire\Admin\Maintenance;

use App\Models\Branch;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use App\Models\Map_States_hr2fms;
use Illuminate\Support\Facades\DB;
use App\Models\Map_Branches_hr2fms;
use Illuminate\Validation\ValidationException;

class PmgiMapBrancheshr2fms extends Component
{
    Use WithPagination;
    
    public $insert = false;
    public $edits = false; 
    public $branch = null;

    public $fms_state_name;
    public $fms_branch_name;
    public $fms_branch_code;
    public $hr_state_name;
    public $hr_branch_name;

    public $filterBranches = [];

    public $user;

    public function loadBranches()
    {
        $stateCode = Map_States_hr2fms::where('fms_state_name', $this->fms_state_name)->value('fms_state_code');

        $this->filterBranches = $this->fms_state_name
            ? Branch::where('state_code', $stateCode)
                ->orderBy('branch_name')
                ->pluck('branch_name')
                ->all()
            : [];
    }

    public function updatedFmsStateName($value)
    {
        $this->loadBranches();

        $exists = collect($this->filterBranches)->pluck('branch_name')->contains($this->fms_branch_name);

        if (!$exists) {
            $this->fms_branch_name = '';
            $this->fms_branch_code = '';            
        }
    }  
      
    public function loadBranchCode()
    {
        $branchCode = Branch::where('branch_name', $this->fms_branch_name)->value('branch_code');

       if ($this->fms_branch_name) {
            $this->fms_branch_code = ltrim($branchCode, '0');            
       }

       else
       {
            $this->fms_branch_code = '';
       }        
    }

    public function updatedFmsBranchName($value)
    {
        $this->loadBranchCode();
    }
        
    public function add()
    {
        $this->insert = true;

        $this->fms_state_name  = '';
        $this->fms_branch_name = '';
        $this->fms_branch_code = '';
        $this->hr_state_name   = '';     
        $this->hr_branch_name  = ''; 
    }

    public function store() 
    {
        try{
            $this->validate([
                'fms_state_name'  => 'required',
                'fms_branch_name' => 'required',
                'fms_branch_code' => 'required',
                'hr_state_name'   => 'required',
                'hr_branch_name' => [
                                      'required',
                                       Rule::unique('pmgi_map_branches_hr2fms', 'hr_branch_name') ->where(fn ($branch_name) => $branch_name->where('fms_branch_code', $this->fms_branch_code)),                                    
                                    ]
            ],
            [
                'fms_state_name.required'  => 'Sila plih nama negeri dalam sistem FMS',
                'fms_branch_name.required' => 'Sila plih nama cawangan dalam sistem FMS',
                'fms_branch_code.required' => 'Sila masukan kod cawangan',
                'hr_state_name.required'   => 'Sila pilih nama negeri dalam sistem HR',
                'hr_branch_name.required'  => 'Sila masukkan nama branch dalam sistem HR',
                'hr_branch_name.unique'    => 'Nama cawangan dalam sistem HR sudah wujud untuk kod cawangan FMS ini'
            ]);
        }

        catch (ValidationException $e)
        {
            // Check which rules failed
            $failed = $e->validator->failed();

            if (isset($failed['hr_branch_name']['Unique'])) 
            {
                $msg = $e->validator->errors()->first('hr_branch_name');
                $this->dispatch('swal', title: $msg, icon: 'error');
                return; // stop here; DON'T rethrow, so no default inline error for unique
            }

            throw $e;
        }

        Map_Branches_hr2fms::create([
            'seq_no'          => DB::table('pmgi_map_branches_hr2fms')->max('seq_no') + 1,
            'fms_state_name'  => $this->fms_state_name,
            'fms_branch_name' => $this->fms_branch_name,
            'fms_branch_code' => $this->fms_branch_code,
            'hr_state_name'   => $this->hr_state_name,
            'hr_branch_name'  => Str::squish(strtoupper($this->hr_branch_name)),
            'upd_flag'        => 'N',
            'created_at'      => \Carbon\Carbon::now('Asia/Kuala_Lumpur'),
            'created_by'      => $this->user,
            'updated_at'      => NULL,
        ]);            

        $this->insert = false; // close modal only

        // Livewire v3 event (name + payload)
        $this->dispatch('swal', title: 'Berjaya', text: 'Berjaya Tambah Senarai Cawangan.', icon: 'success');
        redirect()->route('maintenance.admin.map_brances_hr2fms');
    }

    public function edit($branch)
    {
        $data = Map_Branches_hr2fms::where('seq_no', $branch)->first();

        $this->edits = true;
        $this->branch = $branch;

        $this->fms_state_name  = $data->fms_state_name;
        $this->fms_branch_name = $data->fms_branch_name;
        $this->fms_branch_code = $data->fms_branch_code;
        $this->hr_state_name   = $data->hr_state_name;     
        $this->hr_branch_name  = $data->hr_branch_name;   
    }

    public function update()
    {
        $this->validate([
            'hr_state_name'  => 'required',
            'hr_branch_name' => 'required',                                                           
        ],
        [
            'hr_state_name.required'  => 'Sila pilih nama negeri',
            'hr_branch_name.required' => 'Sila masukkan nama branch dalam sistem HR',
        ]);

        Map_Branches_hr2fms::where('seq_no', $this->branch)->update([
            'hr_state_name'   => $this->hr_state_name,
            'hr_branch_name'  => Str::squish(strtoupper($this->hr_branch_name)),
            'updated_at'      => \Carbon\Carbon::now('Asia/Kuala_Lumpur'),
            'updated_by'      => $this->user,
        ]);
 
        $this->edits = false; // close modal only

        // Livewire v3 event (name + payload)
        $this->dispatch('swal', title: 'Berjaya', text: 'Nama Negeri dan Nama Cawagan Dalam Sistem HR Berjaya Dikemas Kini.', icon: 'success');
        redirect()->route('maintenance.admin.map_brances_hr2fms');      
    }

    public function confirmDelete($branch)
    {
        $data = Map_Branches_hr2fms::where('seq_no', $branch)->first();    

        $this->dispatch('swal:confirm', title: 'Adakah anda pasti mahu menghapuskan senarai cawangan ini?', icon: 'warning', label1:'Negeri (FMS)', label2:'Cawangan (FMS)', label3:'Kod Cawangan FMS', label4:'Negeri (HR)', label5:'Cawangan (HR)', param1:$data->fms_state_name, param2:$data->fms_branch_name, param3:$data->fms_branch_code, param4:$data->hr_state_name, param5:$data->hr_branch_name, key:'branch', param:$branch);
    }
    
    #[On('delete')]
    public function delete($branch)
    {        
        Map_Branches_hr2fms::where('seq_no', $branch)->update([
            'deleted_at' => \Carbon\Carbon::now('Asia/Kuala_Lumpur'), 
            'deleted_by' => $this->user,
            'updated_at' => NULL
        ]);

        $this->dispatch('swal', title: 'Berjaya', text: 'Senarai Cawangan Ini Berjaya Dihapuskan.', icon: 'success');
        redirect()->route('maintenance.admin.map_brances_hr2fms');
    }

    public function close()
    {
        $this->insert = false;
        $this->edits = false;

        $this->resetValidation('fms_state_name');
        $this->resetValidation('fms_branch_name');
        $this->resetValidation('hr_state_name');
        $this->resetValidation('hr_branch_name');
    }

    public function mount()
    {
        $this->user = auth()->user()->USERID;
    }

    public function render()
    {

        $data     = Map_Branches_hr2fms::query()->whereNull(['deleted_at', 'deleted_by'])->orderBy('seq_no', 'asc')->paginate(15);
        $state    = Map_States_hr2fms::select(['fms_state_name', 'hr_state_name'])->get();
        $branches = Branch::select(['branch_code', 'branch_name'])->get();

        return view('livewire.admin.maintenance.pmgi-map-branches-hr2fms', compact('data', 'state', 'branches'))->extends('layouts.main');
    }
}
