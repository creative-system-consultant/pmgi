<?php

namespace App\Livewire\Module;

use Livewire\Component;

class RekodPmgi extends Component
{
    public $showDetail = false;

    public function toggleDetail(): void
    {
        $this->showDetail = !$this->showDetail;
    }

    public function render()
    {
        return view('livewire.module.rekod-pmgi')->extends('layouts.main');
    }
}
