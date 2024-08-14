<?php

namespace App\Livewire\Module\Lantikan\Evaluator;

use App\Models\SettPymPmc;
use Carbon\Carbon;
use Livewire\Component;

class Index extends Component
{
    public $years;
    public $selectedYear;
    public $months;
    public $selectedMonth;

    public function mount()
    {
        $this->getYearsAndMonths();
    }

    private function getYearsAndMonths()
    {
        // Get current date
        $current = Carbon::now();

        $this->selectedYear = $current->year;
        $this->selectedMonth = $current->month;

        // Fetch distinct years from SettPymPmc table
        $uniqueYears = SettPymPmc::selectRaw("DISTINCT to_char(REPORT_DATE, 'YYYY') as year")
                                    ->whereRaw("REPORT_DATE >= TO_DATE('01-01-2024', 'DD-MM-YYYY')")
                                    ->pluck('year')
                                    ->toArray();

        // Check if uniqueYears is empty and add current year if necessary
        if (empty($uniqueYears)) {
            $this->years[] = (string) $current->year;
        } else {
            // Populate years from 2024 onwards
            foreach ($uniqueYears as $year) {
                if ($year >= 2024) {
                    $this->years[] = (string) $year;
                }
            }
        }

        // Populate months
        for ($i = 1; $i <= 12; $i++) {
            $this->months[] = [
                'name' => Carbon::create()->month($i)->translatedFormat('F'), // Month name in Malay
                'value' => $i // Numerical value of the month
            ];
        }
    }

    public function changeYear($newYear)
    {
        $this->selectedYear = $newYear;
        $this->dispatch('selected-year-changed');
        $this->dispatch('refreshPmgi', month: $this->selectedMonth, year: $this->selectedYear);
    }

    public function changeMonth($newMonth)
    {
        $this->selectedMonth = $newMonth;
        $this->dispatch('selected-year-changed');
        $this->dispatch('refreshPmgi', month: $this->selectedMonth, year: $this->selectedYear);
    }

    public function render()
    {
        return view('livewire.module.lantikan.evaluator.index')->extends('layouts.main');
    }
}
