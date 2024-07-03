<?php

namespace App\Livewire\Module;

use Livewire\Component;

class MesyuaratJtt extends Component
{
    public $result;

    public function render()
    {
        return view('livewire.module.mesyuarat-jtt')->extends('layouts.main');
    }
}
