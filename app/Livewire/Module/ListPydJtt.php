<?php

namespace App\Livewire\Module;

use App\Models\JttMeetingInvitation;
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
                $title = 'Perhatian.',
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
        // prod use this
        // $sessionDate = now()->format('Y-m-d');

        // uat jtt1
        $sessionDate = Carbon::createFromFormat('d/m/Y', '01/05/2024')->format('Y-m-d'); //report_date

        // uat jtt2
        // $sessionDate = Carbon::createFromFormat('d/m/Y', '01/10/2024')->format('Y-m-d');

        $data = MntrSession::with('user')
                            ->whereIn('pmgi_level', ['JT1', 'JT2'])
                            ->where('session_date_start', '<=', $sessionDate)
                            ->where('session_date_end', '>=', $sessionDate)
                            ->wherePmgiResult(NULL)
                            ->get();

        $panels = JttMeetingInvitation::with('officer')->where('session_id', $this->sessionId)->get();

        return view('livewire.module.list-pyd-jtt', [
            'datas' => $data,
            'panels' => $panels
        ])->extends('layouts.main');
    }
}
