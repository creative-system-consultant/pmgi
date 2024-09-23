<?php

namespace App\Livewire\Module;

use App\Models\BankOfficer;
use App\Models\BnmStatecode;
use App\Models\Branch;
use App\Models\SessionInfo;
use App\Models\SettPymPmc;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;

class MaklumatWargaKerja extends Component
{
    public $sessionId;
    public $selectedState;
    public $selectedStateDescription;
    public $selectedBranch;
    public $selectedBranchDescription;
    public $staffNo;
    public $pmgiLevel;
    public $pydName;
    public $pydPosition;
    public $pydIc;
    public $meetingType = 1;
    public $venue;
    public $pymName;
    public $pymStaffNo;
    public $pmcName;
    public $pmcStaffNo;

    protected function rules()
    {
        return [
            'meetingType' => 'required',
            'venue' => [
                Rule::requiredIf(function () {
                    return $this->meetingType == 1;
                }),
            ],
        ];
    }

    protected function messages()
    {
        return [
            'meetingType.required' => 'Jenis sesi penilaian diperlukan.',
            'venue.required' => 'Tempat diperlukan jika penilaian dilakukan secara fizikal.',
        ];
    }

    public function mount()
    {
        $this->sessionId = str_replace('-', '/', request()->query('session_id'));

        // fetch setting info if session id present
        if ($this->sessionId) {
            $settInfo = SettPymPmc::where('session_id', $this->sessionId)->first();
            $bankOfficerPyd = BankOfficer::whereOfficerId($settInfo->pyd_id)->first();
            $bankOfficerPym = BankOfficer::whereOfficerId($settInfo->pym_id)->first();

            $this->selectedBranch = $settInfo->branch_code;
            $this->selectedBranchDescription = Branch::where('branch_code', $settInfo->branch_code)->value('branch_name');
            $this->selectedState = substr($this->selectedBranch, 0, 2);
            $this->selectedStateDescription = BnmStatecode::whereCode(substr($this->selectedBranch, 0, 2))->value('description');
            $this->staffNo = $bankOfficerPyd->staffno;
            $this->pmgiLevel = $settInfo->pmgi_level;
            $this->pydName = $bankOfficerPyd->officer_name;
            $this->pydPosition = $bankOfficerPyd->officer_position;
            $this->pydIc = substr($bankOfficerPyd->nokp, 0, 6) . '-' . substr($bankOfficerPyd->nokp, 6, 2) . '-' . substr($bankOfficerPyd->nokp, 8, 4);
            $this->pymName = $bankOfficerPym->officer_name;
            $this->pymStaffNo = $bankOfficerPym->staffno;

            if ($settInfo->pmgi_level == 'PM3') {
                $bankOfficerPmc = BankOfficer::whereOfficerId($settInfo->pmc_id)->first();

                $this->pmcName = $bankOfficerPmc->officer_name;
                $this->pmcStaffNo = $bankOfficerPmc->staffno;
            }
        }
    }

    public function startSession()
    {
        $this->validate();

        SessionInfo::create([
            'session_id' => $this->sessionId,
            'type' => $this->meetingType,
            'venue' => $this->venue,
            'session_date' => now(),
            'created_by' => auth()->user()->userid
        ]);

        $sessionId = str_replace('/', '-', $this->sessionId);
        if ($this->pmgiLevel == 'PM3') {
            return $this->redirect('/pegawai-pemudah-cara?session_id=' . $sessionId);
        } else {
            return $this->redirect('/pegawai-menilai?session_id=' . $sessionId);
        }
    }

    public function render()
    {
        return view('livewire.module.maklumat-warga-kerja')->extends('layouts.main');
    }
}
