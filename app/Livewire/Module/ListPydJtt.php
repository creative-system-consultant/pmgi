<?php

namespace App\Livewire\Module;

use App\Models\MntrSession;
use Carbon\Carbon;
use Livewire\Component;
use WireUi\Traits\Actions;

class ListPydJtt extends Component
{
    use Actions;

    public $sessionId;

    public function mount()
    {
        // Set the test date to November 2024
        // $simulatedDate = Carbon::create(2024, 10, 1, 12);
        // Carbon::setTestNow($simulatedDate);

        $this->sessionId = request()->query('sessionId');

        // check flash error from middleware
        if (session()->has('flash_success')) {
            $this->dialog()->success(
                $title = 'Berjaya!',
                $description = session('flash_success')
            );
        }

        if (session()->has('flash_error')) {
            $this->dialog()->error(
                $title = 'Ralat!',
                $description = session('flash_error')
            );
        }
    }

    public function startSession($userid, $report_date)
    {
        $formatReportDate = Carbon::parse($report_date)->format('Y-m-d');
        return redirect()->route('mesyuarat-jtt', ['sessionId' => $this->sessionId, 'userid' => $userid, 'reportDate' => $formatReportDate]);
    }

    public function render()
    {
        // Set the test date to November 2024
        // $simulatedDate = Carbon::create(2024, 9, 1, 12);
        // Carbon::setTestNow($simulatedDate);

        $data = MntrSession::with('user')
                            ->whereIn('pmgi_level', ['JT1', 'JT2'])
                            ->where('session_date_start', '<=', now()->format('Y-m-d H:i:s'))
                            ->where('session_date_end', '>=', now()->format('Y-m-d H:i:s'))
                            ->wherePmgiResult(NULL)
                            ->get();

        return view('livewire.module.list-pyd-jtt', [
            'datas' => $data
        ])->extends('layouts.main');
    }
}
