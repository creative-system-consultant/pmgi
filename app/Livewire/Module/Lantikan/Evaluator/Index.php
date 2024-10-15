<?php

namespace App\Livewire\Module\Lantikan\Evaluator;

use App\Models\SettPymPmc;
use Carbon\Carbon;
use Livewire\Component;

class Index extends Component
{
    public $current;
    public $selectedYear;
    public $selectedMonth;

    public function mount()
    {
        $this->current = Carbon::now();

        $this->selectedYear = $this->current->year;
        $this->selectedMonth = $this->current->month;
    }

    public function render()
    {
        return view('livewire.module.lantikan.evaluator.index')->extends('layouts.main');
    }
}
