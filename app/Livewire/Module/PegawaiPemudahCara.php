<?php

namespace App\Livewire\Module;

use Livewire\Component;

class PegawaiPemudahCara extends Component
{
    public $perakuan = false;
    public $syorKeluar = "0";
    public $showPrestasiKumulatif = false;
    public $sessionSetting;

    public function togglePrestasiKumulatif()
    {
        $this->showPrestasiKumulatif = !$this->showPrestasiKumulatif;
    }

    public function render()
    {
        return view('livewire.module.pegawai-pemudah-cara')->extends('layouts.main');
    }
}
