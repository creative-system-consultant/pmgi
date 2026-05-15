<?php

namespace App\Livewire\Home;

use App\Models\SettPymPmc;
use Carbon\Carbon;

class Pym extends BasePymPmc
{
    protected function getQuery()
    {
        // prod use this
        // $lastMonthEnd = now()->subMonth()->endOfMonth();

        // uat pmgi 1
        $lastMonthEnd = Carbon::createFromFormat('d/m/Y', '30/04/2024')->format('Y-m-d');

        // uat pmgi 2
        // $lastMonthEnd = Carbon::createFromFormat('d/m/Y', '31/07/2023')->format('Y-m-d');

        // uat pmgi 3
        // $lastMonthEnd = Carbon::createFromFormat('d/m/Y', '30/09/2023')->format('Y-m-d');

        return SettPymPmc::wherePymId(auth()->user()->USERID)
                        ->whereDate('report_date', $lastMonthEnd)
                        ->orderBy('status', 'ASC');
    }

    protected function getTitle()
    {
        return 'Pegawai Yang Menilai (PYM)';
    }

    protected function getSubtitle()
    {
        return 'Senarai PYD yang perlu dinilai';
    }
}
