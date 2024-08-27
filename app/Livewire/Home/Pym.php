<?php

namespace App\Livewire\Home;

use App\Models\SettPymPmc;

class Pym extends BasePymPmc
{
    protected function getQuery()
    {
        return SettPymPmc::wherePymId(auth()->user()->userid)->whereStatus(0);
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
