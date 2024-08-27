<?php

namespace App\Livewire\Module\Prestasi;

use App\Models\SettPymPmc;
use App\Models\SummMthOfficer;
use Carbon\Carbon;
use Livewire\Component;

class Kumulatif extends Component
{
    public $pmgiSession = false;
    public $pmgiSessionId;
    public $pydId;
    public $fromReporDate;
    public $toReportDate;

    public function mount()
    {
        if($this->pmgiSessionId) { // used in pmgi session ; pegawai dinilai/ pegawai menilai/ pegawai mudah cara page when in session
            $setting = SettPymPmc::whereSessionId($this->pmgiSessionId)->first();
            $this->pydId = $setting->pyd_id;
            $report_date = Carbon::parse($setting->report_date);
            $this->fromReporDate = $report_date->copy()->subMonth(2)->endOfMonth()->format('Y-m-d');
            $this->toReportDate = $report_date->copy()->subMonth()->endOfMonth()->format('Y-m-d');
        }
    }

    public function render()
    {
        $from = SummMthOfficer::whereOfficerId($this->pydId)
                                ->whereDate('report_date', $this->fromReporDate)
                                ->first();

        $fromMthName = Carbon::parse($from->report_date)->translatedFormat('F Y');

        $to = SummMthOfficer::whereOfficerId($this->pydId)
                                ->whereDate('report_date', $this->toReportDate)
                                ->first();

        $toMthName = Carbon::parse($to->report_date)->translatedFormat('F Y');

        return view('livewire.module.prestasi.kumulatif', [
            'from' => $from,
            'fromMthName' => $fromMthName,
            'to' => $to,
            'toMthName' => $toMthName,
        ])->extends('layouts.main');
    }
}
