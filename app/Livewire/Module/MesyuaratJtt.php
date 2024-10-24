<?php

namespace App\Livewire\Module;

use App\Models\MntrSession;
use Carbon\Carbon;

use Livewire\Component;
use WireUi\Traits\Actions;

class MesyuaratJtt extends Component
{
    use Actions;

    public $sessionId;
    public $userId;
    public $reportDate;
    public $staffNo;
    public $state;
    public $branch;
    public $staffName;

    public function mount()
    {
        // check flash error from middleware
        if (session()->has('flash_success')) {
            $this->dialog()->success(
                $title = 'Berjaya!',
                $description = session('flash_success')
            );
        }

        $this->sessionId = request()->query('sessionId');
        $this->userId = request()->query('userid');
        $this->reportDate = Carbon::parse(request()->query('reportDate'));

        $data = MntrSession::with('user', 'state', 'branch')
                            ->whereOfficerId($this->userId)
                            ->whereDate('report_date', $this->reportDate)
                            // ->wherePmgiWq('Y')
                            ->first();

        $this->staffNo = $data->user->bankOfficer->staffno;
        $this->state = $data->state->description;
        $this->branch = $data->branch->branch_name;
        $this->staffName = $data->user->username;
    }

    public function render()
    {
        return view('livewire.module.mesyuarat-jtt', [])->extends('layouts.main');
    }
}
