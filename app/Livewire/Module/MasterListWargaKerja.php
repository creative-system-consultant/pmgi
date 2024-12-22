<?php

namespace App\Livewire\Module;

use App\Models\BankOfficer;
use App\Models\HrdOfficer;
use Livewire\Component;
use Livewire\WithPagination;

class MasterListWargaKerja extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $filterTerm = '';

    public function search()
    {
        // Update the filterTerm to match the searchTerm
        $this->filterTerm = $this->searchTerm;
        // Reset pagination to page 1 when a new search is performed
        $this->resetPage();
    }

    public function render()
    {
        $wargaKerja = HrdOfficer::whereHas('bankOfficer', function ($query) {
            $query->where('fms_userstatus', 1);
        })
            ->when($this->filterTerm, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama', 'like', '%' . strtoupper($this->filterTerm) . '%');
                });
            })
            ->orderBy('negeri', 'ASC')
            ->orderBy('cawangan', 'ASC')
            ->paginate(10);

        return view('livewire.module.master-list-warga-kerja', ['wargaKerja' => $wargaKerja])->extends('layouts.main');
    }
}
