<?php

namespace App\Livewire\Module;

use App\Constants\PMGI\PmgiCancelReason;
use App\Events\PMGI\SessionUpdated;
use App\Models\BankOfficer;
use App\Models\MntrSession;
use App\Models\SessionInfo;
use App\Models\SessionPmcInfo;
use App\Models\SettOfficerInfoFile;
use App\Models\SettPymPmc;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\Actions;

class PegawaiPemudahCara extends Component
{
    use Actions, WithFileUploads;

    public $perakuan = false;
    public $showPrestasiKumulatif = false;
    public $showRekodPmgi = false;
    public $sessionId;
    public $pydName;
    public $pydPosition;
    public $pydStaffNo;
    public $pydBranch;
    public $pydState;
    public $stateBranch;
    public $fairFlag;
    public $fairComment;
    public $undrstdFlag;
    public $others;
    public $exitFlag = 1;
    public $exitTypeFlag;
    public $comment;
    public $savedFile;
    public $file;
    public $attachment;
    public $attachment2;
    public $attachment3;
    public $infoModal = false;
    public $attachmentUrl = null;
    public $attachmentModal = false;
    public $sessionSetting;
    public $pydId;
    public $pmcId;
    public $reasonCancel;
    public $cancelSessionModal = false;
    public $buttonRekodPS = false;

    protected $listeners = ['pmgi-session-updated' => 'handleSessionUpdate'];

    protected function rules()
    {
        return [
            'fairFlag' => 'required',
            'fairComment' => 'required|min:3',
            'undrstdFlag' => 'required',
            'exitFlag' => $this->perakuan ? 'required' : 'nullable',
            'exitTypeFlag' => ($this->perakuan && $this->exitFlag == 1) ? 'required' : 'nullable',
            'comment' => $this->perakuan ? 'required|min:3' : 'nullable',
        ];
    }

    protected function messages()
    {
        return [
            'fairFlag.required' => 'Sila Pilih.',
            'fairComment.required' => 'Sila tuliskan ulasan bagi PYD dinilai.',
            'undrstdFlag.required' => 'Sila Pilih.',
            'exitFlag.required' => 'Sila Pilih.',
            'exitTypeFlag.required' => 'Sila Pilih Jenis Penangguhan.',
            'comment.required' => 'Sila tuliskan ulasan anda.',
        ];
    }

    public function mount()
    {
        // check flash error from middleware
        if (session()->has('flash_error')) {
            $this->dialog()->error(
                $title = 'Perhatian.',
                $description = session('flash_error')
            );
        }

        $this->sessionId = str_replace('-', '/', request()->query('session_id'));
        $this->savedFile = SettOfficerInfoFile::where('OFFICER_LVL', 'PMC')->firstOrFail();
        $this->sessionSetting = SettPymPmc::whereSessionId($this->sessionId)->first();

        if ($this->sessionId) {
            $this->pydId = $this->sessionSetting->pyd_id;
            $bankOfficer = BankOfficer::whereOfficerId($this->pydId)->first();
            $this->pydName = $bankOfficer->officer_name;
            $this->pydPosition = $bankOfficer->officer_position;
            $this->pydStaffNo = $bankOfficer->staffno;
            $this->pydBranch = $bankOfficer->branch->branch_name ?? '-';
            $this->pydState = $bankOfficer->branch->bnmState->description ?? '-';
            $this->stateBranch = $this->pydState . ' - ' . $this->pydBranch;
            $this->pmcId = $this->sessionSetting->pmc_id;

            // check if previous PMGi 3 has penilaian semula (EXP)
            $psExists = MntrSession::query()
                ->whereOfficerId($this->pydId)
                ->wherePmgiLevel('PM3')
                ->wherePmgiResult('EXP')
                ->exists();

            $this->buttonRekodPS = $psExists ? true : false;

            // used in perakuan
            $pmcRecordExists = SessionPmcInfo::where('session_id', $this->sessionId)->exists();
            if($pmcRecordExists) {
                $data = SessionPmcInfo::where('session_id', $this->sessionId)->first();
                $this->fairFlag = $data->fair_flag;
                $this->fairComment = $data->fair_comments;
                $this->undrstdFlag = $data->undrstd_flag;
                $this->others = $data->others;
                $this->exitFlag = $data->exit_flag;
                $this->exitTypeFlag = $data->exit_type_flag;
                $this->comment = $data->comments;
                $this->attachment = $data->attachment;
                $this->attachment2 = $data->attachment2;
                $this->attachment3 = $data->attachment3;
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

    private function storeFile($file, $index)
    {
        if (!$file) return null;

        $userid = substr($this->sessionId, 13);
        $folder = str_replace('/', '-', $this->sessionId);

        $ext = $file->getClientOriginalExtension();

        // NAMA FAIL BARU
        $filename = "attachment_PMC_{$index}_" . now()->format('YmdHis') . "." . $ext;

        $store_path = "public/pmgi_session/{$userid}/{$folder}";
        $db_path = "pmgi_session/{$userid}/{$folder}/{$filename}";

        $file->storeAs($store_path, $filename);

        return $db_path;
    }

    public function goToRekodPS()
    {
        return redirect()->route('/rekod-penilaian-semula?session_id' . $this->sessionId);
    }

    public function openInfo()
    {
        $this->infoModal = true;
    }

    public function submit()
    {
        $this->validate();

        $path1 = $this->storeFile($this->file1, 1);
        $path2 = $this->storeFile($this->file2, 2);
        $path3 = $this->storeFile($this->file3, 3);

        SessionPmcInfo::updateOrCreate(
            ['session_id' => $this->sessionId],
            [
                'session_id' => $this->sessionId,
                'fair_flag' => $this->fairFlag,
                'fair_comments' => $this->fairComment,
                'undrstd_flag' => $this->undrstdFlag,
                'others' => $this->others,
                'attachment' => $path1,
                'attachment2' => $path2,
                'attachment3' => $path3,
                'created_by' => auth()->user()->USERID,
            ]
        );

        $sessionId  = str_replace('/', '-', $this->sessionId);

        return $this->redirect('/loading-pmgi?session_id=' . $sessionId . '&source=pmc');
    }

    private function processFile()
    {
        if($this->file) {
            $extension = $this->file->getClientOriginalExtension();
            $userid = substr($this->sessionId, 13); //get userid from sessionId
            $folder = str_replace('/', '-', $this->sessionId);
            $filename = 'PMC_'. $this->pmcId . '_' . $folder . '_' .now()->format('YmdHis') . '.' . $extension;
            $store_path = 'public/pmgi_session/' . $userid . '/' . $folder;
            $db_path = 'pmgi_session/' . $userid . '/' . $folder . '/' . $filename;
            $this->file->storeAs($store_path, $filename);

            return $db_path;
        }
        return;
    }

    public function updatedExitFlag($value)
    {
        if($value == 0)
        {
            $this->exitTypeFlag = null;
            $this->resetValidation('exitTypeFlag');
        }
    }
    
    public function validateExitFields()
    {
        if ($this->exitFlag == 1) {
            if (empty($this->exitTypeFlag) || $this->exitTypeFlag == 0) {
                $this->addError('exitTypeFlag', $this->messages('exitTypeFlag'));
                return false;
            }
        }
        
        return true;
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

        if(!$this->validateExitFields()) return;
        
        $updates = [
            'fair_flag' => $this->fairFlag,
            'fair_comments' => $this->fairComment,
            'undrstd_flag' => $this->undrstdFlag,
            'others' => $this->others,
            'exit_flag' => $this->exitFlag,
            'exit_type_flag' => $this->exitTypeFlag,
            'comments' => $this->comment,
            'updated_by' => $this->pmcId
        ];

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

        SessionPmcInfo::whereSessionId($this->sessionId)->update($updates);

        event(new SessionUpdated(
            $this->sessionId,
            'pmc',
            $updates,
            $this->pmcId
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
        if($role === 'pmc')
        {
            $this->fairFlag = $payload['fair_flag'] ?? $this->fairFlag;
            $this->fairComment = $payload['fair_comments'] ?? $this->fairComment;
            $this->undrstdFlag = $payload['undrstd_flag'] ?? $this->undrstdFlag;
            $this->others = $payload['others'] ?? $this->others;
            $this->exitFlag = $payload['exit_flag'] ?? $this->exitFlag;
            $this->exitTypeFlag = $payload['exit_type_flag'] ?? $this->exitTypeFlag;
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
        $this->validate([
            'reasonCancel' => 'required'
        ],
        [
            '*.required' => 'Sila pilih sebab pembatalan sesi'
        ]);

        $sessionInfo = SessionInfo::query()->whereSessionId($this->sessionId)->first();
        $sessionInfo->update([
            'status' => PmgiSessionStatus::Cancel,
            'reason' => $this->reasonCancel,
        ]);
        $this->sessionSetting->update([
            'status' => PmgiSessionStatus::Cancel,
        ]);

        redirect()->route('home');
    }

    public function render()
    {
        $reasonList = PmgiCancelReason::getReasonList();

        return view('livewire.module.pegawai-pemudah-cara', [
            'reasonList' => $reasonList
        ])->extends('layouts.main');
    }
}
