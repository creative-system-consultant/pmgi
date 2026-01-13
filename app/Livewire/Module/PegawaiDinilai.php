<?php

namespace App\Livewire\Module;

use App\Events\PMGI\SessionUpdated;
use App\Models\BankOfficer;
use App\Models\SessionPydInfo;
use App\Models\SettOfficerInfoFile;
use App\Models\SettPydProb;
use App\Models\SettPymPmc;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\Actions;

class PegawaiDinilai extends Component
{
    use Actions, WithFileUploads;

    public $problemSelection;
    public $sessionId;
    public $sessionSetting;

    public $pydName;
    public $pydPosition;
    public $pydStaffNo;
    public $pydId;
    public $pydBranch;
    public $pydState;
    public $stateBranch;

    public $perakuan = false; 

    public $savedFile;

    #[Validate('required')]
    public $problem;

    #[Validate('required|min:3')]
    public $reason;

    #[Validate('required|min:3')]
    public $actionPlan;

    public $comment;

    #[Validate('nullable|file|max:20480|mimes:jpg,jpeg,png,pdf')]
    public $file1;

    #[Validate('nullable|file|max:20480|mimes:jpg,jpeg,png,pdf')]
    public $file2;

    #[Validate('nullable|file|max:20480|mimes:jpg,jpeg,png,pdf')]
    public $file3;

    public $attachment;
    public $attachment2;
    public $attachment3;

    public $attachmentUrl = null;
    public $attachmentModal = false;

    public $infoModal = false;

    public $showPrestasiKumulatif = false;
    public $showRekodPmgi = false;

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
        
        $this->problemSelection = SettPydProb::orderBy('id')->get()->toArray();
        $this->savedFile = SettOfficerInfoFile::where('OFFICER_LVL', 'PYD')->first();

        $this->sessionId = str_replace('-', '/', request()->query('session_id'));
        $this->sessionSetting = SettPymPmc::whereSessionId($this->sessionId)->first();

        $this->pydId = $this->sessionSetting->pyd_id;

        $officer = BankOfficer::with(['branch.bnmState'])
                    ->whereOfficerId($this->pydId)
                    ->first();

        $this->pydName     = $officer->officer_name;
        $this->pydPosition = $officer->officer_position;
        $this->pydStaffNo  = $officer->staffno;
        $this->pydBranch   = $officer->branch->branch_name ?? '-';
        $this->pydState    = $officer->branch->bnmState->description ?? '-';
        $this->stateBranch = $this->pydState . ' - ' . $this->pydBranch;

        $data = SessionPydInfo::where('session_id', $this->sessionId)->first();

        if ($data) {
            $this->problem     = (int) $data->problem;
            $this->reason      = $data->reason;
            $this->actionPlan  = $data->action;
            $this->comment     = $data->comments;

            $this->attachment = $data->attachment;
            $this->attachment2 = $data->attachment2;
            $this->attachment3 = $data->attachment3;
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

    public function openInfo()
    {
        $this->infoModal = true;
    }

    private function storeFile($file, $index)
    {
        if (!$file) return null;

        $userid = substr($this->sessionId, 13);
        $folder = str_replace('/', '-', $this->sessionId);

        $ext = $file->getClientOriginalExtension();

        // NAMA FAIL BARU
        $filename = "attachment_PYD_{$index}_" . now()->format('YmdHis') . "." . $ext;

        $store_path = "public/pmgi_session/{$userid}/{$folder}";
        $db_path = "pmgi_session/{$userid}/{$folder}/{$filename}";

        $file->storeAs($store_path, $filename);

        return $db_path;
    }

    public function toggleDetail($db_path)
    {
        $this->attachmentUrl = asset('storage/' . $db_path);
        $this->attachmentModal = true;
    }

    public function submit()
    {
        $this->validate();

        $path1 = $this->storeFile($this->file1, 1);
        $path2 = $this->storeFile($this->file2, 2);
        $path3 = $this->storeFile($this->file3, 3);

        SessionPydInfo::updateOrCreate(
            ['session_id' => $this->sessionId],
            [
                'problem'       => $this->problem,
                'reason'        => $this->reason,
                'action'        => $this->actionPlan,
                'comments'      => $this->comment,
                'attachment'    => $path1 ?: $this->attachment,
                'attachment2'   => $path2 ?: $this->attachment2,
                'attachment3'   => $path3 ?: $this->attachment3,
                'created_by'    => auth()->user()->USERID,
            ]
        );

        $sessionId = str_replace('/', '-', $this->sessionId);

        return redirect('/loading-pmgi?session_id='. $sessionId . '&source=pyd');
    }


    public function updates()
    {
        $this->dialog()->confirm([
            'title'       => 'Andakah anda pasti?',
            'description' => 'Kemaskini maklumat ini?',
            'icon'        => 'question',
            'accept'      => ['label' => 'Ya', 'method' => 'confirmUpdate'],
            'reject'      => ['label' => 'Tidak'],
        ]);
    }


    public function confirmUpdate()
    {
        $this->validate();

        $updates = [
            'problem'  => $this->problem,
            'reason'   => $this->reason,
            'action'   => $this->actionPlan,
            'comments' => $this->comment,
            'updated_by' => $this->pydId
        ];

        // hanya overwrite kalau user upload fail baru
        if ($this->file1) {
            $updates['attachment'] = $this->storeFile($this->file1, 1);
            $this->attachment = $updates['attachment'];
        }
        if ($this->file2) {
            $updates['attachment2'] = $this->storeFile($this->file2, 2);
            $this->attachment2 = $updates['attachment2'];
        }
        if ($this->file3) {
            $updates['attachment3'] = $this->storeFile($this->file3, 3);
            $this->attachment3 = $updates['attachment3'];
        }

        SessionPydInfo::whereSessionId($this->sessionId)->update($updates);

        event(new SessionUpdated(
            $this->sessionId,
            'pyd',
            $updates,
            $this->pydId
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
        if($role === 'pyd')
        {
            $this->problem = $payload['problem'] ?? $this->problem;
            $this->reason = $payload['reason'] ?? $this->reason;
            $this->actionPlan = $payload['action'] ?? $this->actionPlan;
            $this->comment = $payload['comments'] ?? $this->comment;
            $this->attachment = $payload['attachment'] ?? $this->attachment;
            $this->attachment2 = $payload['attachment2'] ?? $this->attachment2;
            $this->attachment3 = $payload['attachment3'] ?? $this->attachment3;
        }

        // Show notification
        $this->dialog()->info(
            title: 'Kemaskini Sesi',
            description: 'Sesi telah dikemaskini oleh ' . strtoupper($role)
        );
    }


    public function render()
    {
        return view('livewire.module.pegawai-dinilai')->extends('layouts.main');
    }
}
