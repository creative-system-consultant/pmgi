<?php

namespace App\Livewire\Module\Tetapan\UserAccessLevel;

use Livewire\Component;

class Index extends Component
{
    public $showDetail = false;

    public function render()
    {
        return view('livewire.module.tetapan.user-access-level.index')->extends('layouts.main');
    }
}
