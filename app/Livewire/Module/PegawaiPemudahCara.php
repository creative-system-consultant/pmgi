<?php

namespace App\Livewire\Module;

use App\Models\BankOfficer;
use App\Models\SessionPmcInfo;
use App\Models\SettPymPmc;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\Actions;

class PegawaiPemudahCara extends Component
{
    use Actions, WithFileUploads;

    public $perakuan = false;
    public $showPrestasiKumulatif = false;
    public $sessionId;
    public $pydName;
    public $pydPosition;
    public $pydStaffNo;
    public $fairFlag;
    public $fairComment;
    public $undrstdFlag;
    public $others;
    public $exitFlag = 0;
    public $exitTypeFlag;
    public $comment;
    public $file;
    public $attachment;
    public $sessionSetting;

    protected function rules()
    {
        return [
            'fairFlag' => 'required',
            'fairComment' => 'required|min:3',
            'undrstdFlag' => 'required',
            'exitFlag' => 'required',
            'exitTypeFlag' => [
                Rule::requiredIf(function () {
                    return $this->exitFlag == 1;
                }),
            ],
            'comment' => 'required|min:3',
        ];
    }

    protected function messages()
    {
        return [
            'fairFlag.required' => 'Sila Pilih.',
            'fairComment.required' => 'Sila tuliskan ulasan bagi PYD dinilai.',
            'undrstdFlag.required' => 'Sila Pilih.',
            'exitFlag.required' => 'Sila Pilih.',
            'exitTypeFlag.required' => 'Sila Pilih.',
            'comment.required' => 'Sila tuliskan ulasan anda.',
        ];
    }

    public function mount()
    {
        // check flash error from middleware
        if (session()->has('flash_error')) {
            $this->dialog()->error(
                $title = 'Ralat!',
                $description = session('flash_error')
            );
        }

        $this->sessionId = str_replace('-', '/', request()->query('session_id'));
        $this->sessionSetting = SettPymPmc::whereSessionId($this->sessionId)->first();

        if ($this->sessionId) {
            $pyd = $this->sessionSetting->pyd_id;
            $bankOfficer = BankOfficer::whereOfficerId($pyd)->first();
            $this->pydName = $bankOfficer->officer_name;
            $this->pydPosition = $bankOfficer->officer_position;
            $this->pydStaffNo = $bankOfficer->staffno;

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
            }
        }
    }

    public function togglePrestasiKumulatif()
    {
        $this->showPrestasiKumulatif = !$this->showPrestasiKumulatif;
    }

    public function submit()
    {
        $this->validate();

        $path = $this->processFile();

        SessionPmcInfo::create([
            'session_id' => $this->sessionId,
            'fair_flag' => $this->fairFlag,
            'fair_comments' => $this->fairComment,
            'undrstd_flag' => $this->undrstdFlag,
            'others' => $this->others,
            'exit_flag' => $this->exitFlag,
            'exit_type_flag' => $this->exitTypeFlag,
            'comments' => $this->comment,
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
            $filename = 'attachment_PMC_' . now()->format('YmdHis') . '.' . $extension;
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
        SessionPmcInfo::whereSessionId($this->sessionId)->update([
            'fair_flag' => $this->fairFlag,
            'fair_comments' => $this->fairComment,
            'undrstd_flag' => $this->undrstdFlag,
            'others' => $this->others,
            'exit_flag' => $this->exitFlag,
            'exit_type_flag' => $this->exitTypeFlag,
            'comments' => $this->comment,
        ]);

        $this->dialog()->success(
            $title = 'Berjaya!',
            $description = 'Ulasan berjaya dikemaskini'
        );
    }

    public function render()
    {
        return view('livewire.module.pegawai-pemudah-cara')->extends('layouts.main');
    }
}
