<?php

namespace App\Livewire\Home;

use App\Models\MntrSession;
use Livewire\Component;

class Hr extends Component
{
    public function startSession($userid)
    {
        $this->redirectRoute('hr.index', ['userid' => $userid]);
    }

    public function render()
    {
        $data = MntrSession::wherePmgiLevel('HRD')
                            ->whereNull('pmgi_result')
                            ->get();

        return view('livewire.home.hr', [
            'datas' => $data,
        ]);
    }
}
