<?php

namespace App\Livewire\Module;

use App\Models\BankOfficer;
use App\Models\HrdOfficer;
use Livewire\Component;
use Livewire\WithPagination;

class MasterListWargaKerja extends Component
{
    use WithPagination;

    public function render()
    {
        $wargaKerja = HrdOfficer::orderBy('negeri', 'ASC')->orderBy('cawangan', 'ASC')->paginate(10);

        return view('livewire.module.master-list-warga-kerja', ['wargaKerja' => $wargaKerja])->extends('layouts.main');
    }
}
