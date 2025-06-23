<?php

namespace App\Livewire\Module\Lantikan\Evaluator;

use App\Models\SettPymPmc;
use Carbon\Carbon;
use Livewire\Component;

class Index extends Component
{
    public $current;

    public function mount()
    {
        // prod used this
        // $this->current = Carbon::now();

        // uat pmgi 1
        $this->current = Carbon::createFromFormat('d/m/Y', '01/02/2023'); // session_date_start

        // uat pmgi 2
        // $this->current = Carbon::createFromFormat('d/m/Y', '01/07/2023'); // session_date_start

        // uat pmgi 3
        // $this->current = Carbon::createFromFormat('d/m/Y', '01/12/2023'); // session_date_start
    }

    public function render()
    {
        return view('livewire.module.lantikan.evaluator.index')->extends('layouts.main');
    }
}
