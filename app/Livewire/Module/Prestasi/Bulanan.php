<?php

namespace App\Livewire\Module\Prestasi;

use Livewire\Component;

class Bulanan extends Component
{
    public function render()
    {
        return view('livewire.module.prestasi.bulanan')->extends('layouts.main');
    }
}
