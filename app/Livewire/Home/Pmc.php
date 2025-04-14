<?php

namespace App\Livewire\Home;

use App\Models\SettPymPmc;
use Carbon\Carbon;

class Pmc extends BasePymPmc
{
    protected function getQuery()
    {
        // prod use this
        // $lastMonthEnd = now()->subMonth()->endOfMonth();

        // uat pmgi 3
        $lastMonthEnd = Carbon::createFromFormat('d/m/Y', '30/11/2023')->format('Y-m-d');

        return SettPymPmc::wherePmcId(auth()->user()->userid)
                            ->whereDate('report_date', $lastMonthEnd)
                            ->wherePmgiLevel('PM3')
                            ->orderBy('status', 'ASC');
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
