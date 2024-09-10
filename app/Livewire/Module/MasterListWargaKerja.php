<?php

namespace App\Livewire\Module;

use App\Models\BankOfficer;
use Livewire\Component;
use Livewire\WithPagination;

class MasterListWargaKerja extends Component
{
    use WithPagination;

    public function render()
    {
        $wargaKerja = BankOfficer::select('officer_id', 'officer_name', 'OFFICER_POSITION', 'email', 'nokp', 'STAFFNO', 'HR_MGR_FLAG', 'DATE_RESIGN')->paginate(10);
        return view('livewire.module.master-list-warga-kerja', ['wargaKerja' => $wargaKerja])->extends('layouts.main');
    }
}
