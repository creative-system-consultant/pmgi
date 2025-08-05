<?php

namespace App\Livewire\Module\Prestasi;

use App\Exports\PrestasiBulananKeseluruhan;
use App\Exports\PrestasiBulananRingkasan;
use App\Models\BankOfficer;
use App\Models\BnmStatecode;
use App\Models\Branch;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Bulanan extends Component
{
    public $type;
    public $state;
    public $branch;
    public $staffName;
    public $pydId;
    public $date;
    public $role;
    public $result = false;

    protected function rules()
    {
        $rules = [
            'type' => 'required',
            'date' => 'required',
        ];

        if ($this->role != 'pyd') {
            $rules['state'] = 'required';
            $rules['branch'] = [
                Rule::requiredIf(function () {
                    return $this->state;
                }),
            ];
            // staffName is optional - if provided, filter by specific staff; if empty, show all staff
        }

        return $rules;
    }

    protected function messages()
    {
        return [
            'type.required' => 'Sila pilih jenis laporan.',
            'state.required' => 'Sila pilih negeri.',
            'branch.required' => 'Sila pilih cawangan.',
            'date.required' => 'Sila pilih bulan.',
        ];
    }

    public function mount()
    {
        if (hasRoles('PYD')) {
            $this->role = 'pyd';
            $this->pydId = auth()->user()->USERID;
        } else {
            $this->role = 'admin';
        }
    }

    public function updatedType()
    {
        $this->result = false;
        $this->reset('state','branch', 'staffName', 'date');
    }

    public function updatedState()
    {
        $this->result = false;
        $this->reset('branch', 'staffName');
    }

    public function updatedBranch()
    {
        $this->result = false;
        $this->reset('staffName');
    }

    public function updatedStaffName()
    {
        $this->result = false;
        
        if ($this->staffName) {
            $this->staffName = strtoupper($this->staffName);
            
            $this->pydId = BankOfficer::whereBranchCode($this->branch)
                                      ->where(function($q) {
                                          $q->where('officer_name', 'LIKE', '%' . $this->staffName . '%')
                                            ->orWhere('staffno', 'LIKE', '%' . $this->staffName . '%');
                                      })
                                      ->value('officer_id');
        } else {
            // If staff name is empty, clear pydId to show all staff data
            $this->pydId = null;
        }
    }

    public function generate(): void
    {
        $this->validate();

        $this->result = true;
    }

    public function download()
    {
        if ($this->type == 1) {
            return Excel::download(new PrestasiBulananRingkasan($this->date, $this->state, $this->branch, $this->pydId), 'PRESTASI_BULANAN_RINGKASAN.xlsx');
        } else {
            return Excel::download(new PrestasiBulananKeseluruhan($this->date, $this->state, $this->branch, $this->pydId), 'PRESTASI_BULANAN_KESELURUHAN.xlsx');
        }
    }

    public function render()
    {
        $stateSelection = BnmStatecode::select('code', 'description')
            ->whereNotIn('code', ['00', '15', '16', '99'])
            ->orderBy('code', 'ASC')
            ->get();

        // Add 'SEMUA NEGERI' to the beginning as an object
        $stateSelection->prepend((object) [
            'code' => '%',
            'description' => 'SEMUA NEGERI',
        ]);

        // Handle branch selection based on the selected state
        if ($this->state && $this->state == '%') {
            // 'SEMUA NEGERI' selected
            $branchSelection = collect([
                (object) [
                    'branch_code' => '%%',
                    'branch_name' => 'SEMUA CAWANGAN',
                ],
            ]);
        } elseif ($this->state && $this->state != '%') {
            // A specific state is selected
            $branchSelection = Branch::select('branch_name', 'branch_code')
                ->whereNotIn('closeflag', [1])
                ->whereBranchType('BRN')
                ->whereHideflag(0)
                ->whereStateCode($this->state) // Use state code from selection
                ->orderBy('branch_name', 'ASC')
                ->get();

            // Add 'SEMUA CAWANGAN' to the beginning
            $branchSelection->prepend((object) [
                'branch_code' => '%%',
                'branch_name' => 'SEMUA CAWANGAN',
            ]);
        } else {
            // Default empty collection if no state is selected
            $branchSelection = collect();
        }

        return view('livewire.module.prestasi.bulanan', [
            'stateSelection' => $stateSelection,
            'branchSelection' => $branchSelection,
        ])->extends('layouts.main');
    }
}
