<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        return view('livewire.home')->extends('layouts.main');
    }
}
