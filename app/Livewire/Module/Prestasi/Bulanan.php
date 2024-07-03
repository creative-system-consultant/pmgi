<?php

namespace App\Livewire\Module\Prestasi;

use Livewire\Component;

class Bulanan extends Component
{
    public $type;
    public $result = false;

    public function generate(): void
    {
        $this->result = true;
    }

    public function render()
    {
        return view('livewire.module.prestasi.bulanan')->extends('layouts.main');
    }
}
