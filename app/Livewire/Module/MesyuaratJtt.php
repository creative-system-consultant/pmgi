<?php

namespace App\Livewire\Module;

use App\Models\MntrSession;
use App\Models\SessionJttPydInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PDO;
use WireUi\Traits\Actions;

class MesyuaratJtt extends Component
{
    use Actions;

    public $sessionId;
    public $userId;
    public $reportDate;
    public $staffNo;
    public $state;
    public $branch;
    public $staffName;
    public $pmgiLevel;
    public $pmgi1Data;
    public $pmgi2Data;
    public $pmgi3Data;
    public $result;
    public $mthDelay;
    public $comment;

    public function mount()
    {
        // Set the test date to November 2024
        // $simulatedDate = Carbon::create(2024, 10, 1, 12);
        // Carbon::setTestNow($simulatedDate);

        // check flash error from middleware
        if (session()->has('flash_success')) {
            $this->dialog()->success(
                $title = 'Berjaya!',
                $description = session('flash_success')
            );
        }

        $this->sessionId = request()->query('sessionId');
        $this->userId = request()->query('userid');
        $this->reportDate = Carbon::parse(request()->query('reportDate'));

        $data = MntrSession::with('user', 'state', 'branch')
                            ->whereOfficerId($this->userId)
                            ->whereDate('report_date', $this->reportDate)
                            // ->wherePmgiWq('Y')
                            ->first();

        $this->staffNo = $data->user->bankOfficer->staffno;
        $this->state = $data->state->description;
        $this->branch = $data->branch->branch_name;
        $this->staffName = $data->user->username;
        $this->pmgiLevel = $data->pmgi_level;
        $this->getPmgiData($data->pmgi_cycle, $this->pmgiLevel);
    }

    public function getPmgiData($cycle, $level)
    {
        $this->pmgi1Data = MntrSession::with('settPymPmc')
                            ->whereOfficerId($this->userId)
                            ->wherePmgiCycle($cycle)
                            ->wherePmgiResult('CP1')
                            ->first();

        $this->pmgi2Data = MntrSession::with('settPymPmc')
                            ->whereOfficerId($this->userId)
                            ->wherePmgiCycle($cycle)
                            ->wherePmgiResult('CP2')
                            ->first();

        $this->pmgi3Data = MntrSession::with('settPymPmc')
                            ->whereOfficerId($this->userId)
                            ->wherePmgiCycle($cycle)
                            ->wherePmgiResult('NEX')
                            ->first();

        if ($level == 'JT2') {
            // $pmgi3Data = MntrSession::with('settPymPmc')
            //                 ->whereOfficerId($this->userId)
            //                 ->wherePmgiCycle($cycle)
            //                 ->wherePmgiResult('NEX')
            //                 ->first();
        }
    }

    public function submit()
    {
        // save data
        SessionJttPydInfo::create([
            'session_id' => $this->sessionId,
            'officer_id' => $this->userId,
            'result' => $this->result,
            'mth_delay' => $this->mthDelay,
            'comments' => $this->comment
        ]);

        $resultSp = $this->runSp();

        // Redirect to the next page or do whatever action you need
        if (substr($resultSp, 0, 1) == '0') {
            return redirect()->route('list-pyd-jtt', ['sessionId' => $this->sessionId])->with('flash_success', 'Sesi selesai dilaksanakan.');
        } else {
            $this->dialog()->error(
                $title = 'Ralat!',
                $description = "Masalah Server."
            );
        }
    }

    public function runSp()
    {
        if ($this->pmgiLevel == 'JT1') {
            if ($this->result == 'Diberi Tempoh') {
                $pmgiResult = 'PDQ';
            } else {
                $pmgiResult = 'DQ1';
            }
        } else {
            if ($this->result == 'Keluar Senarai') {
                $pmgiResult = 'EXL';
            } else {
                $pmgiResult = 'DQ2';
            }
        }

        $data = MntrSession::whereOfficerId($this->userId)
                            ->whereDate('report_date', $this->reportDate)
                            ->first();

        $output = '';

        $procedureName = 'dbo.UP_PMGI_NAZ_UPD_MNTR_SESSION';

        $bindings = [
            'pi_reportdt'    => Carbon::parse($data->report_date)->format('Y-m-d'),
            'pi_state_code'  => $data->state_code,
            'pi_branch_code' => $data->branch_code,
            'pi_officer_id'  => $data->officer_id,
            'pi_pmgi_result' => $pmgiResult,
            'pi_wait_period' => 0,
            'pi_operated_by' => 'SYSTEM',
            'pi_ret_msg'     => [
                'value' => &$output,
                'type'  => PDO::PARAM_STR,
                'length' => 4000,
            ],
        ];

        // Execute the procedure
        DB::executeProcedure($procedureName, $bindings);

        return $output;
    }

    public function render()
    {
        return view('livewire.module.mesyuarat-jtt', [])->extends('layouts.main');
    }
}
