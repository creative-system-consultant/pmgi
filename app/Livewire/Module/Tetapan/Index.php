<?php

namespace App\Livewire\Module\Tetapan;

use Livewire\Component;

class Index extends Component
{
    public $showDetail = false;

    public function render()
    {
        return view('livewire.module.tetapan.index')->extends('layouts.main');
    }
}
