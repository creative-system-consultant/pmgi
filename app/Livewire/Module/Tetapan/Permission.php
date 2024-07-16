<?php

namespace App\Livewire\Module\Tetapan;

use Livewire\Component;
use WireUi\Traits\Actions;

class Permission extends Component
{
    use Actions;

    public $results = [];
    public $addPermissionModal = false;

    public function add()
    {
        $this->addPermissionModal = true;
    }

    public function render()
    {
        return view('livewire.module.tetapan.permission', [
            'results' => $this->results,
        ]);
    }
}
