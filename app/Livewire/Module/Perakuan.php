<?php

namespace App\Livewire\Module;

use Livewire\Component;

class Perakuan extends Component
{
    public function render()
    {
        return view('livewire.module.perakuan')->extends('layouts.main');
    }
}
