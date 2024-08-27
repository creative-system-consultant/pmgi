<?php

namespace App\Livewire;

use App\Models\SettPymPmc;
use Livewire\Component;
use WireUi\Traits\Actions;

class Home extends Component
{
    use Actions;

    public function mount()
    {
        // check flash error from middleware
        if (session()->has('flash_error')) {
            $this->dialog()->error(
                $title = 'Ralat!',
                $description = session('flash_error')
            );
        }

        if (session()->has('flash_success')) {
            $this->dialog()->success(
                $title = 'Berjaya!',
                $description = session('flash_success')
            );
        }
    }

    public function render()
    {
        return view('livewire.home')->extends('layouts.main');
    }
}
