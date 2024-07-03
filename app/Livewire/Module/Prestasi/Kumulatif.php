<?php

namespace App\Livewire\Module\Prestasi;

use Livewire\Component;

class Kumulatif extends Component
{
    public $search = true;

    public function render()
    {
        return view('livewire.module.prestasi.kumulatif')->extends('layouts.main');
    }
}
