<?php

namespace App\Livewire\Home;

use Livewire\Component;

class Ptt extends Component
{
    public function startMeeting()
    {
        return $this->redirect('/dashboard-jtt');
    }

    public function render()
    {
        return view('livewire.home.ptt');
    }
}
