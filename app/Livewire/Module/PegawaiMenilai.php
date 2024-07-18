<?php

namespace App\Livewire\Module;

use App\Models\SettOfficerInfoFile;
use Livewire\Component;

class PegawaiMenilai extends Component
{
    public $search = true;
    public $edit = false;
    public $showPrestasiKumulatif = false;
    public $savedFile;
    public $infoModal = false;

    public function mount()
    {
        $this->savedFile = SettOfficerInfoFile::where('OFFICER_LVL', 'PYM')->first();
    }

    public function togglePrestasiKumulatif()
    {
        $this->showPrestasiKumulatif = !$this->showPrestasiKumulatif;
    }

    public function openInfo()
    {
        $this->infoModal = true;
    }

    public function render()
    {
        return view('livewire.module.pegawai-menilai')->extends('layouts.main');
    }
}
