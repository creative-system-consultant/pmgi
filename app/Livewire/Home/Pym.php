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
        $lastMonthEnd = Carbon::createFromFormat('d/m/Y', '31/01/2023')->format('Y-m-d');

        return SettPymPmc::wherePymId(auth()->user()->userid)
                        ->whereDate('report_date', $lastMonthEnd)
                        ->whereStatus(0);
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
