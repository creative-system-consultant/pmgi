<?php

namespace App\Livewire\Admin\Maintenance;

use App\Models\SettOfficerInfoFile;
use App\Models\SoalanLazimFile;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\Actions;

class SoalanLazim extends Component
{
    public function render()
    {
        return view('livewire.admin.maintenance.soalan-lazim')
            ->extends('layouts.main');
    }
}
