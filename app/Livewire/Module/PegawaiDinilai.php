<?php

namespace App\Livewire\Module;

use Livewire\Component;

class PegawaiDinilai extends Component
{
    public $search = true;
    public $edit = false;
    public $model;
    public $showPrestasiKumulatif = false;

    public function togglePrestasiKumulatif()
    {
        $this->showPrestasiKumulatif = !$this->showPrestasiKumulatif;
    }

    public function render()
    {
        return view('livewire.module.pegawai-dinilai')->extends('layouts.main');
    }
}
