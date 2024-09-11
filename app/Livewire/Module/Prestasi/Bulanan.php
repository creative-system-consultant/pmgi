<?php

namespace App\Livewire\Module\Prestasi;

use App\Models\BnmStatecode;
use App\Models\Branch;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Bulanan extends Component
{
    public $type;
    public $state;
    public $branch;
    public $date;
    public $role;
    public $result = false;

    protected function rules()
    {
        return [
            'type' => 'required',
            'state' => [
                Rule::requiredIf(function () {
                    return $this->role == 'admin';
                }),
            ],
            'branch' => [
                Rule::requiredIf(function () {
                    return $this->role == 'admin' && $this->state;
                }),
            ],
            'date' => 'required',
        ];
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
        if (hasRoles('ADMINISTRATOR')) {
            $this->role = 'admin';
        }
    }

    public function updatedType()
    {
        $this->result = false;
        $this->reset('state','branch', 'date');
    }

    public function updatedState()
    {
        $this->result = false;
        $this->reset('branch');
    }

    public function generate(): void
    {
        $this->validate();

        $this->result = true;
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
