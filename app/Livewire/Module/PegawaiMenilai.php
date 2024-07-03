<?php

namespace App\Livewire\Module;

use Livewire\Component;

class PegawaiMenilai extends Component
{
    public $search = true;
    public $edit = false;
    public $showPrestasiKumulatif = false;

    public function togglePrestasiKumulatif()
    {
        $this->showPrestasiKumulatif = !$this->showPrestasiKumulatif;
    }

    public function render()
    {
        return view('livewire.module.pegawai-menilai')->extends('layouts.main');
    }
}
