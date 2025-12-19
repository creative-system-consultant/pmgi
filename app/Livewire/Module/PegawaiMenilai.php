<?php

namespace App\Livewire\Module;

use App\Constants\PMGI\PmgiCancelReason;
use App\Events\PMGI\SessionUpdated;
use App\Models\BankOfficer;
use App\Models\SessionInfo;
use App\Models\SessionPymInfo;
use App\Models\SettOfficerInfoFile;
use App\Models\SettPymPmc;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\Actions;

class PegawaiMenilai extends Component
{
    use Actions, WithFileUploads;

    public $perakuan = false;
    public $showPrestasiKumulatif = false;
    public $showRekodPmgi = false;

    public $savedFile;
    public $infoModal = false;

    public $attachmentUrl = null;
    public $attachmentModal = false;

    public $sessionId;
    public $sessionSetting;

    public $pydId;
    public $pydName;
    public $pydPosition;
    public $pydStaffNo;
    public $pydBranch;
    public $pydState;
    public $stateBranch;
    public $pmgiLevel;

    public $pymId;

    public $reasonCancel;
    public $cancelSessionModal = false;

    #[Validate('required', message: 'Sila tuliskan ulasan bagi PYD dinilai.')]
    #[Validate('min:3', message: 'Sila tuliskan punca lebih dari 3 perkataan')]
    public $comment;

    #[Validate('required', message: 'sila tuliskan pelan tindakan anda.')]
    #[Validate('min:3', message: 'Sila tuliskan pelan tindakan anda lebih dari 3 perkataan')]
    public $actionPlan;

    // ====== FAIL LAMPIRAN BARU ======
    #[Validate('nullable|file|max:20480|mimes:jpg,jpeg,png,gif,pdf,doc,docx')]
    public $file1;

    #[Validate('nullable|file|max:20480|mimes:jpg,jpeg,png,gif,pdf,doc,docx')]
    public $file2;

    #[Validate('nullable|file|max:20480|mimes:jpg,jpeg,png,gif,pdf,doc,docx')]
    public $file3;

    // path yang sedia ada dalam DB
    public $attachment;   // lampiran 1
    public $attachment2;  // lampiran 2
    public $attachment3;  // lampiran 3

    
    protected $listeners = ['pmgi-session-updated' => 'handleRealtimeUpdate'];

    public function mount()
    {
        // check flash error from middleware
        if (session()->has('flash_error')) {
            $this->dialog()->error(
                $title = 'Perhatian.',
                $description = session('flash_error')
            );
        }

        $this->savedFile = SettOfficerInfoFile::where('OFFICER_LVL', 'PYM')->first();

        $this->sessionId = str_replace('-', '/', request()->query('session_id'));
        $this->sessionSetting = SettPymPmc::whereSessionId($this->sessionId)->first();
        $this->pmgiLevel = $this->sessionSetting->pmgi_level;

        if ($this->sessionId && $this->sessionSetting) {

            $this->pydId = $this->sessionSetting->pyd_id;
            $this->pymId = $this->sessionSetting->pym_id;

            $bankOfficer = BankOfficer::with(['branch.bnmState'])
                ->whereOfficerId($this->pydId)
                ->first();

            if ($bankOfficer) {
                $this->pydName     = $bankOfficer->officer_name;
                $this->pydPosition = $bankOfficer->officer_position;
                $this->pydStaffNo  = $bankOfficer->staffno;
                $this->pydBranch   = $bankOfficer->branch->branch_name ?? '-';
                $this->pydState    = $bankOfficer->branch->bnmState->description ?? '-';
                $this->stateBranch = $this->pydState . ' - ' . $this->pydBranch;
            }

            // rekod PYM sedia ada
                $data = SessionPymInfo::where('session_id', $this->sessionId)->first();

            if ($data) {
                $this->comment    = $data->comments;
                $this->actionPlan = $data->action;

                $this->attachment  = $data->attachment;   // lampiran 1
                $this->attachment2 = $data->attachment2;  // lampiran 2
                $this->attachment3 = $data->attachment3;  // lampiran 3
            }
        }
    }


    public function togglePrestasiKumulatif()
    {
        $this->showPrestasiKumulatif = !$this->showPrestasiKumulatif;
    }

    public function toggleRekodPmgi()
    {
        $this->showRekodPmgi = !$this->showRekodPmgi;
    }

    public function toggleDetail()
    {
        if ($this->file) {
            $this->attachmentUrl = $this->file->temporaryUrl();
        } else if($this->attachment) {
            $this->attachmentUrl = asset('storage/' . $this->attachment);
        }
        $this->attachmentModal = true;
    }

    public function openInfo()
    {
        $this->infoModal = true;
    }

    // ====== SIMPAN SATU FAIL (IKUT SLOT 1/2/3) ======
    private function storeFile($file, int $slot)
    {
        if (!$file) {
            return null;
        }

        // contoh: sessionId = PMG12511/1/AZHARSU
        // userid = AZHARSU
        $userid = substr($this->sessionId, 13);

        // folder = PMG12511-1-AZHARSU
        $folder = str_replace('/', '-', $this->sessionId);

        $ext = $file->getClientOriginalExtension();

        // ikut pattern lama: attachment_PYM_1_YYYYMMDDHHIISS.ext
        $filename = 'attachment_PYM_' . $slot . '_' . now()->format('YmdHis') . '.' . $ext;

        $store_path = 'public/pmgi_session/' . $userid . '/' . $folder;
        $db_path    = 'pmgi_session/' . $userid . '/' . $folder . '/' . $filename;

        $file->storeAs($store_path, $filename);

        return $db_path;
    }


    public function submit()
    {
        $this->validate();

        $path1 = $this->storeFile($this->file1, 1);
        $path2 = $this->storeFile($this->file2, 2);
        $path3 = $this->storeFile($this->file3, 3);

        SessionPymInfo::updateOrCreate([
            'session_id'   => $this->sessionId,
            'comments'     => $this->comment,
            'action'       => $this->actionPlan,
            'attachment'    => $path1 ?: $this->attachment,
            'attachment2'   => $path2 ?: $this->attachment2,
            'attachment3'   => $path3 ?: $this->attachment3,
            'created_by'   => auth()->user()->USERID,
        ]);

        $sessionId = str_replace('/', '-', $this->sessionId);

        return $this->redirect('/loading-pmgi?session_id=' . $sessionId . '&source=pym');
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
        $this->validate();
        
        // hanya overwrite kalau user upload fail baru
        if ($this->file1) {
            $update['attachment'] = $this->storeFile($this->file1, 1);
            $this->attachment = $update['attachment'];
        }
        if ($this->file2) {
            $update['attachment2'] = $this->storeFile($this->file2, 2);
            $this->attachment2 = $update['attachment2'];
        }
        if ($this->file3) {
            $update['attachment3'] = $this->storeFile($this->file3, 3);
            $this->attachment3 = $update['attachment3'];
        }

        $update = [
            'comments' => $this->comment,
            'action'   => $this->actionPlan,
            'attachment' => $update['attachment'],
            'attachment2' => $update['attachment2'],
            'attachment3' => $update['attachment3'],
            'updated_by' => $this->pymId
        ];

        SessionPymInfo::whereSessionId($this->sessionId)->update($update);

        event(new SessionUpdated(
            $this->sessionId,
            'pym',
            $update,
            $this->pymId
        ));

        $this->dialog()->success('Berjaya!', 'Ulasan telah dikemaskini.');
    }

    #[On('pmgi-session-updated')]
    public function handleSessionUpdate($role, $payload)
    {
        // Skip if current user made the update
        if (isset($payload['updated_by']) && $payload['updated_by'] === auth()->user()->USERID) {
            return;
        }

        // Update the properties based on role
        if($role === 'pym')
        {
            $this->actionPlan = $payload['action'] ?? $this->actionPlan;
            $this->comment = $payload['comments'] ?? $this->comment;
        }

        // Show notification
        $this->dialog()->info(
            title: 'Kemaskini Sesi',
            description: 'Sesi telah dikemaskini oleh ' . strtoupper($role)
        );
    }

    public function cancelSessionConfirm()
    {
        $this->cancelSessionModal = true;
    }

    public function close()
    {
        $this->cancelSessionModal = false;
        $this->resetValidation('reasonCancel');
    }

    public function confirmCancel()
    {
        $this->validate(
            [ 'reasonCancel' => 'required' ],
            [ '*.required'   => 'Sila pilih sebab pembatalan sesi' ]
        );

        $sessionInfo = SessionInfo::query()
            ->whereSessionId($this->sessionId)
            ->first();

        $sessionInfo->update([
            'status' => 2,
            'reason' => $this->reasonCancel,
        ]);

        $this->sessionSetting->update([
            'status' => 2,
        ]);

        redirect()->route('home');
    }

    public function render()
    {
        $reasonList = PmgiCancelReason::getReasonList();

        return view('livewire.module.pegawai-menilai', [
            'reasonList' => $reasonList
        ])->extends('layouts.main');
    }
}
