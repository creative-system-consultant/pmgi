<?php

namespace App\Livewire\Module;

use App\Models\BankOfficer;
use App\Models\SessionPydInfo;
use App\Models\SettOfficerInfoFile;
use App\Models\SettPydProb;
use App\Models\SettPymPmc;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\Actions;

class PegawaiDinilai extends Component
{
    use Actions, WithFileUploads;

    public $problemSelection;
    public $perakuan = false;
    public $model;
    public $savedFile;
    public $showPrestasiKumulatif = false;
    public $infoModal = false;
    public $attachmentUrl = null;
    public $attachmentModal = false;
    public $sessionId;
    public $pydName;
    public $pydPosition;
    public $pydStaffNo;

    #[Validate('required', message: 'Sila pilih masalah yang dihadapi.')]
    public $problem;

    #[Validate('required', message: 'Sila tuliskan punca bagi masalah yang dipilih.')]
    #[Validate('min:3', message: 'Sila tuliskan punca lebih dari 3 perkataan')]
    public $reason;

    #[Validate('required', message: 'sila tuliskan pelan tindakan anda.')]
    #[Validate('min:3', message: 'Sila tuliskan pelan tindakan anda lebih dari 3 perkataan')]
    public $actionPlan;
    public $comment;
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

        $this->problemSelection = SettPydProb::orderBy('id', 'asc')->get()->toArray();
        $this->savedFile = SettOfficerInfoFile::where('OFFICER_LVL', 'PYD')->first();

        $this->sessionId = str_replace('-', '/', request()->query('session_id'));
        $this->sessionSetting = SettPymPmc::whereSessionId($this->sessionId)->first();
        if ($this->sessionId) {
            $pyd = $this->sessionSetting->pyd_id;
            $bankOfficer = BankOfficer::whereOfficerId($pyd)->first();
            $this->pydName = $bankOfficer->officer_name;
            $this->pydPosition = $bankOfficer->officer_position;
            $this->pydStaffNo = $bankOfficer->staffno;

            // used in perakuan
            $pydRecordExists = SessionPydInfo::where('session_id', $this->sessionId)->exists();
            if($pydRecordExists) {
                $data = SessionPydInfo::where('session_id', $this->sessionId)->first();
                $this->problem = (int) $data->problem;
                $this->reason = $data->reason;
                $this->actionPlan = $data->action;
                $this->comment = $data->comments;
                $this->attachment = $data->attachment;
            }
        }
    }

    public function togglePrestasiKumulatif()
    {
        $this->showPrestasiKumulatif = !$this->showPrestasiKumulatif;
    }

    public function toggleDetail()
    {
        if ($this->file) {
            $this->attachmentUrl = $this->file->temporaryUrl();
        }
        $this->attachmentModal = true;
    }

    public function openInfo()
    {
        $this->infoModal = true;
    }

    public function submit()
    {
        $this->validate();

        $path = $this->processFile();

        SessionPydInfo::create([
            'session_id' => $this->sessionId,
            'problem' => $this->problem,
            'reason' => $this->reason,
            'action' => $this->actionPlan,
            'comments' => $this->comment,
            'attachment' => $path,
            'created_by' => auth()->user()->userid,
        ]);

        $sessionId  = str_replace('/', '-', $this->sessionId);

        return $this->redirect('/loading-pmgi?session_id=' . $sessionId . '&source=pyd');
    }

    private function processFile()
    {
        if($this->file) {
            $extension = $this->file->getClientOriginalExtension();
            $userid = substr($this->sessionId, 11); //get userid from sessionId
            $folder = str_replace('/', '-', $this->sessionId);
            $filename = 'attachment_PYD_' . now()->format('YmdHis') . '.' . $extension;
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
        SessionPydInfo::whereSessionId($this->sessionId)->update([
            'problem' => $this->problem,
            'reason' => $this->reason,
            'action' => $this->actionPlan,
            'comments' => $this->comment,
        ]);

        $this->dialog()->success(
            $title = 'Berjaya!',
            $description = 'Ulasan berjaya dikemaskini'
        );
    }

    public function render()
    {
        return view('livewire.module.pegawai-dinilai')->extends('layouts.main');
    }
}
