<?php

namespace App\Livewire\Module\Tetapan\OfficerInfo;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.module.tetapan.officer-info.index')->extends('layouts.main');
    }
}
