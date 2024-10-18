<?php

namespace App\Livewire\Home;

use App\Models\SettPymPmc;
use Carbon\Carbon;

class Pmc extends BasePymPmc
{
    protected function getQuery()
    {
        // uat pmgi 3
        $lastMonthEnd = Carbon::createFromFormat('d/m/Y', '31/07/2023')->format('Y-m-d');

        // $lastMonthEnd = now()->subMonth()->endOfMonth();

        return SettPymPmc::wherePmcId(auth()->user()->userid)
                            ->whereDate('report_date', $lastMonthEnd)
                            ->wherePmgiLevel('PM3')
                            ->whereStatus(0);
    }

    protected function getTitle()
    {
        return 'Pegawai Mudah Cara (PMC)';
    }

    protected function getSubtitle()
    {
        return 'Senarai PYD yang perlu dinilai';
    }
}
