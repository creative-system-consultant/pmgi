<?php

namespace App\Livewire\Home;

use App\Models\SettPymPmc;

class Pmc extends BasePymPmc
{
    protected function getQuery()
    {
        return SettPymPmc::wherePmcId(auth()->user()->userid)
                            ->wherePmgiLevel('PM3');
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
