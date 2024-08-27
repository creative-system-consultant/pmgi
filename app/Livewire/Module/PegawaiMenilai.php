<?php

namespace App\Livewire\Module;

use App\Models\BankOfficer;
use App\Models\SessionPymInfo;
use App\Models\SettOfficerInfoFile;
use App\Models\SettPymPmc;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\Actions;

class PegawaiMenilai extends Component
{
    use Actions, WithFileUploads;

    public $perakuan = false;
    public $showPrestasiKumulatif = false;
    public $savedFile;
    public $infoModal = false;
    public $sessionId;
    public $pydName;
    public $pydPosition;
    public $pydStaffNo;

    #[Validate('required', message: 'Sila tuliskan ulasan bagi PYD dinilai.')]
    #[Validate('min:3', message: 'Sila tuliskan punca lebih dari 3 perkataan')]
    public $comment;

    #[Validate('required', message: 'sila tuliskan pelan tindakan anda.')]
    #[Validate('min:3', message: 'Sila tuliskan pelan tindakan anda lebih dari 3 perkataan')]
    public $actionPlan;
    public $file;
    public $attachment;
    public $sessionSetting;

    public function mount()
    {

        // check flash error from middleware
        if (session()->has('flash_error')) {
            $this->dialog()->error(
                $title = 'Ralat!',
                $description = session('flash_error')
            );
        }

        $this->savedFile = SettOfficerInfoFile::where('OFFICER_LVL', 'PYM')->first();

        $this->sessionId = str_replace('-', '/', request()->query('session_id'));
        $this->sessionSetting = SettPymPmc::whereSessionId($this->sessionId)->first();
        if ($this->sessionId) {
            $pyd = $this->sessionSetting->pyd_id;
            $bankOfficer = BankOfficer::whereOfficerId($pyd)->first();
            $this->pydName = $bankOfficer->officer_name;
            $this->pydPosition = $bankOfficer->officer_position;
            $this->pydStaffNo = $bankOfficer->staffno;

            // used in perakuan
            $pymRecordExists = SessionPymInfo::where('session_id', $this->sessionId)->exists();
            if($pymRecordExists) {
                $data = SessionPymInfo::where('session_id', $this->sessionId)->first();
                $this->comment = $data->comments;
                $this->actionPlan = $data->action;
                $this->attachment = $data->attachment;
            }
        }
    }

    public function togglePrestasiKumulatif()
    {
        $this->showPrestasiKumulatif = !$this->showPrestasiKumulatif;
    }

    public function openInfo()
    {
        $this->infoModal = true;
    }

    public function submit()
    {
        $this->validate();

        $path = $this->processFile();

        SessionPymInfo::create([
            'session_id' => $this->sessionId,
            'comments' => $this->comment,
            'action' => $this->actionPlan,
            'attachment' => $path,
            'created_by' => auth()->user()->userid,
        ]);

        $sessionId  = str_replace('/', '-', $this->sessionId);

        return $this->redirect('/loading-pmgi?session_id=' . $sessionId . '&source=pym');
    }

    private function processFile()
    {
        if($this->file) {
            $extension = $this->file->getClientOriginalExtension();
            $userid = substr($this->sessionId, 11); //get userid from sessionId
            $folder = str_replace('/', '-', $this->sessionId);
            $filename = 'attachment_PYM_' . now()->format('YmdHis') . '.' . $extension;
            $store_path = 'public/pmgi_session/' . $userid . '/' . $folder;
            $db_path = 'pmgi_session/' . $userid . '/' . $folder . '/' . $filename;
            $this->file->storeAs($store_path, $filename);

            return $db_path;
        }
        return;
    }

    public function updates()
    {
        $this->dialog()->confirm([
            'title'       => 'Andakah anda pasti?',
            'description' => 'Kemaskini maklumat ini?',
            'icon'        => 'question',
            'accept'      => [
                'label'  => 'Ya, pasti',
                'method' => 'confirmUpdate',
            ],
            'reject' => [
                'label'  => 'Tidak, batalkan',
            ],
        ]);
    }

    public function confirmUpdate()
    {
        SessionPymInfo::whereSessionId($this->sessionId)->update([
            'comments' => $this->comment,
            'action' => $this->actionPlan,
        ]);

        $this->dialog()->success(
            $title = 'Berjaya!',
            $description = 'Ulasan berjaya dikemaskini'
        );
    }

    public function render()
    {
        return view('livewire.module.pegawai-menilai')->extends('layouts.main');
    }
}
