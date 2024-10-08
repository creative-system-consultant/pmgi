<?php

namespace App\Livewire\Home;

use App\Models\SettPymPmc;

class Pmc extends BasePymPmc
{
    protected function getQuery()
    {
        $lastMonthEnd = now()->subMonth()->endOfMonth();

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
