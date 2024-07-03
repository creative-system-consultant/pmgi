<?php

namespace App\Livewire\Module;

use Livewire\Component;
use WireUi\Traits\Actions;

class Perakuan extends Component
{
    use Actions;

    public $verifyModal = false;

    public function verify(): void
    {
        $this->verifyModal = true;
    }

    public function render()
    {
        return view('livewire.module.perakuan')->extends('layouts.main');
    }
}
