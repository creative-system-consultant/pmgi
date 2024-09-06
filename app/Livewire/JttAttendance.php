<?php

namespace App\Livewire;

use Livewire\Component;

class JttAttendance extends Component
{
    public $status;
    public $title;
    public $subtitle;

    public function mount($status)
    {
        $this->status = $status;

        if ($this->status == 'notFound') {
            $this->title = "Gagal!";
            $this->subtitle = "Token tidak sah.";
        } elseif ($this->status == 'expired') {
            $this->title = "Gagal!";
            $this->subtitle = "Token tamat tempoh. Token hanya sah untuk 1 jam sahaja.";
        } else {
            $this->title = "Berjaya!";
            $this->subtitle = "Kehadiran anda dalam sesi ini telah direkod kan.";
        }
    }

    public function render()
    {
        return view('livewire.jtt-attendance')->extends('layouts.main');
    }
}
