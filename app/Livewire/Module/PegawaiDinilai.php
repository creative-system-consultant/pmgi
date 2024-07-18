<?php

namespace App\Livewire\Module;

use App\Models\SettOfficerInfoFile;
use App\Models\SettPydProb;
use Livewire\Component;

class PegawaiDinilai extends Component
{
    public $problem;
    public $search = true;
    public $edit = false;
    public $model;
    public $savedFile;
    public $showPrestasiKumulatif = false;
    public $infoModal = false;

    public function mount()
    {
        $this->problem = SettPydProb::all()->toArray();
        $this->savedFile = SettOfficerInfoFile::where('OFFICER_LVL', 'PYD')->first();
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
        return view('livewire.module.pegawai-dinilai')->extends('layouts.main');
    }
}
