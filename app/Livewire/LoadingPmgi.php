<?php

namespace App\Livewire;

use App\Models\SessionPydInfo;
use App\Models\SessionPymInfo;
use Livewire\Component;
use WireUi\Traits\Actions;

class LoadingPmgi extends Component
{
    use Actions;

    public $sessionId;
    public $source;
    public $title;
    public $subtitle;

    public function mount()
    {
        $this->showError();
        $this->fetchQueryString();
        $this->setText();
    }

    protected function showError()
    {
        // check flash error from middleware
        if (session()->has('flash_error')) {
            $this->dialog()->error(
                $title = 'Ralat!',
                $description = session('flash_error')
            );
        }
    }

    protected function fetchQueryString()
    {
        $this->sessionId = str_replace('-', '/', request()->query('session_id'));
        $this->source = request()->query('source');
    }

    protected function setText()
    {
        if ($this->source == 'pyd') {
            $this->title = 'Sila tunggu penilai membuat penilaian.';
            $this->subtitle = 'Halaman ini akan dialihkan secara automatik apabila tugas penilai selesai.';
        }

        if ($this->source == 'pym') {
            $this->title = 'Sila tunggu pegawai dinilai membuat ulasan.';
            $this->subtitle = 'Halaman ini akan dialihkan secara automatik apabila tugas pegawai dinilai selesai.';
        }

        if ($this->source == 'pmc') {
            $this->title = 'Sila tunggu PYD dan PYM membuat ulasan.';
            $this->subtitle = 'Halaman ini akan dialihkan secara automatik apabila tugas PYD dan PYM selesai.';
        }
    }

    public function checkRecord()
    {
        // Check if records exist in the respective tables with the session ID
        $pydRecordExists = SessionPydInfo::where('session_id', $this->sessionId)->exists();
        $pymRecordExists = SessionPymInfo::where('session_id', $this->sessionId)->exists();

        if ($pydRecordExists && $pymRecordExists) {
            // Create a single redirect URL based on the source
            $encodedSessionId = str_replace('/', '-', $this->sessionId);
            return $this->redirect('/perakuan?session_id='.$encodedSessionId.'&source='.$this->source);
        }
    }

    public function render()
    {
        return view('livewire.loading-pmgi')->extends('layouts.main');
    }
}
