<?php

namespace App\Livewire\Home;

use Livewire\Component;

abstract class BasePymPmc extends Component
{
    public function startSession($id)
    {
        $sessionId = str_replace('/', '-', $id);

        return $this->redirect('/maklumat-warga-kerja?session_id=' . $sessionId);
    }

    abstract protected function getQuery();

    abstract protected function getTitle();

    abstract protected function getSubtitle();

    public function render()
    {
        $data = $this->getQuery()->get();

        return view('livewire.home.base-pym-pmc', [
            'datas' => $data,
            'title' => $this->getTitle(),
            'subtitle' => $this->getSubtitle(),
        ]);
    }
}
