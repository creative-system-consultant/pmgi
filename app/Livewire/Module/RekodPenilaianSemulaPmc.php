<?php

namespace App\Livewire\Module;

use App\Models\BankOfficer;
use App\Models\MntrSession;
use App\Models\SessionPmcInfo;
use App\Models\SettPymPmc;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RekodPenilaianSemulaPmc extends Component
{
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
    public $exitFlag;
    public $exitTypeFlag;
    public $comment;
    public $attachment;
    public $attachmentUrl = null;
    public $attachmentModal = false;
    public $sessionSetting;
    public $pydId;
    public $pmcId;
    public $previousRecords;

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
        
        if(!$this->sessionId)
        {
            return;
        }

        $this->sessionSetting = SettPymPmc::whereSessionId($this->sessionId)->first();

        if(!$this->sessionSetting)
        {
            return;
        }

        $this->pydId = $this->sessionSetting->pyd_id;
        $this->pmcId = $this->sessionSetting->pmc_id;

        $bankOfficer = BankOfficer::query()
            ->with('branch.bnmState')
            ->whereOfficerId($this->pydId)
            ->first();

        if($bankOfficer)
        {
            $this->pydName = $bankOfficer->officer_name;
            $this->pydPosition = $bankOfficer->officer_position;
            $this->pydStaffNo = $bankOfficer->staffno;
            $this->pydBranch = $bankOfficer->branch->branch_name ?? '-';
            $this->pydState = $bankOfficer->branch->bnmState->description ?? '-';
            $this->stateBranch = $this->pydState . ' - ' . $this->pydBranch;
        }

        $pmgi3Exists = DB::table('PMGI_MNTR_SESSION as ms')
                    ->join('PMGI_SETT_PYM_PMC as sp', function ($join) {
                        $join->on('ms.report_date', '=', 'sp.report_date')
                            ->where('sp.pyd_id', '=', $this->pydId); // Match on both report_date and officer_id (pyd_id)
                    })
                    ->join('PMGI_SESSION_PMC_INFO as spmc', function ($join) {
                        $join->on('sp.session_id', '=', 'spmc.session_id');
                    })
                    ->where('ms.officer_id', '=', $this->pydId)
                    ->where('ms.pmgi_level', '=', 'PM3')
                    ->where('ms.pmgi_result', '=', 'EXP')
                    // ->whereIn('ms.pmgi_level', ['PM3', 'JT1'])
                    // ->whereIn('ms.pmgi_result', ['EXP', 'PDQ'])
                    ->orderBy('ms.seq_no', 'ASC')
                    ->select(
                        'ms.seq_no as seq',
                        'ms.pmgi_level as lvl',
                        'sp.pym_id', // We’ll use this to retrieve the pym's username
                        'sp.pmc_id', // We’ll use this to retrieve the pmc's username (if needed)
                        'sp.session_id',
                        'sp.created_at as date_session',
                        'ms.pmgi_result',
                        'spmc.fair_flag',
                        'spmc.fair_comments',
                        'spmc.undrstd_flag',
                        'spmc.others',
                        'spmc.exit_flag',
                        'spmc.exit_type_flag',
                        'spmc.comments',
                        'spmc.attachment',
                    )
                    ->get();

        $resultMapping = [
            'CP1' => 'SELESAI DILAKSANAKAN',
            'CP2' => 'SELESAI DILAKSANAKAN',
            'PEX' => 'DISYORKAN KELUAR TANPA SYARAT',
            'EXC' => 'DISYORKAN KELUAR DENGAN SYARAT',
            'EXP' => 'DITANGGUHKAN',
            'NEX' => 'DIHANTAR KE SESI TIMBANG TARA',
            'EX1' => 'KELUAR SENARAI',
            'PDQ' => 'DIBERI TEMPOH',
            'DQ1' => 'TINDAKAN TATATERTIB',
            'EXL' => 'KELUAR SENARAI',
            'DQ2' => 'TINDAKAN TATATERTIB',
            'PDM' => 'DITAMATKAN PERKHIDMATAN',
            'NDM' => 'PERKHIDMATAN DISAMBUNG',
        ];

        $this->previousRecords = $pmgi3Exists->map(function ($data) use ($resultMapping) {
            // Map pmgi_result code to its description
            $pmgiResult = $resultMapping[$data->pmgi_result] ?? 'RESULT UNKNOWN';

            // Get the pym and pmc usernames (if needed) by loading the related User model
            $pym = BankOfficer::find($data->pym_id);
            $pmc = BankOfficer::find($data->pmc_id);

            // Initialize the array with common fields
            $result = [
                'seq' => $data->seq,
                'lvl' => $data->lvl,
                'pym' => $pym ? $pym->officer_name : 'N/A',
                'session_id' => $data->session_id,
                'date_session' => Carbon::parse($data->date_session)->format('d/m/Y'),
                'result' => $pmgiResult,
                'fair_flag' => $data->fair_flag,
                'fair_comments' => $data->fair_comments,
                'undrstd_flag' => $data->undrstd_flag,
                'others' => $data->others,
                'exit_flag' => $data->exit_flag,
                'exit_type_flag' => $data->exit_type_flag,
                'comments' => $data->comments,
                'attachment' => $data->attachment,
            ];

            // Add 'pmc' field conditionally if pmgi_level is 'PM3'
            if ($data->lvl == 'PM3' && $pmc) {
                $result['pmc'] = $pmc->officer_name;
            }

            return $result;
        });
    }

    public function toggleModal($attachmentPath)
    {
        if ($attachmentPath) {
            $this->attachmentUrl = asset('storage/' . $attachmentPath);
        }
        $this->attachmentModal = true;
    }

    public function render()
    {
        return view('livewire.module.rekod-penilaian-semula-pmc')->extends('layouts.main');
    }
}
