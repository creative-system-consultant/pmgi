<?php

namespace App\Livewire\Module\Hr;

use App\Models\HrdInfo;
use App\Models\MntrSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PDO;

class Index extends Component
{
    public $userId;
    public $mntrData;
    public $staffNo;
    public $state;
    public $branch;
    public $staffName;
    public $effectiveDate;
    public $result;
    public $dateUntil;

    public function mount($userid)
    {
        $this->userId = $userid;

        $data = DB::select('
            SELECT ms.*, u.username, bo.staffno, s.description as state_description, b.branch_name
            FROM PMGI_MNTR_SESSION ms
            JOIN fms_users u ON ms.officer_id = u.userid
            JOIN bank_officers bo ON u.userid = bo.officer_id
            JOIN bnm_statecodes s ON ms.state_code = s.code
            JOIN branches b ON ms.branch_code = b.branch_code
            WHERE ms.officer_id = :officer_id AND ms.pmgi_level = :pmgi_level
            AND ROWNUM = 1
        ', ['officer_id' => $userid, 'pmgi_level' => 'HRD']);

        if (!empty($data)) {
            $this->mntrData = $data[0];
            $this->staffNo = $this->mntrData->staffno;
            $this->state = $this->mntrData->state_description;
            $this->branch = $this->mntrData->branch_name;
            $this->staffName = $this->mntrData->username;
        }
    }

    public function updatedResult($value)
    {
        $this->result = $value;
    }

    public function updatedDateUntil($value)
    {
        $this->dateUntil = $value;
    }

    public function submit()
    {
        // save data
        HrdInfo::create([
            'report_date' => $this->mntrData->report_date,
            'officer_id' => $this->userId,
            'effective_date' => $this->effectiveDate,
            'di_result' => $this->result,
            'date_until' => $this->dateUntil,
            'created_by' => auth()->user()->userid,
            'created_at' => now(),
        ]);

        $resultSp = $this->runSp();

        // Redirect to the next page or do whatever action you need
        if (substr($resultSp, 0, 1) == '0') {
            return redirect()->route('home')->with('flash_success', 'Maklumat DI telah dikemaskini.');
        } else {
            $this->dialog()->error(
                $title = 'Ralat!',
                $description = "Masalah Server."
            );
        }
    }

    public function runSp()
    {
        if ($this->result === 1) {
            $pmgiResult = 'PDM';
        } else {
            $pmgiResult = 'NDM';
        }

        $output = '';

        $procedureName = 'dbo.UP_PMGI_UPD_MNTR_SESSION';

        $bindings = [
            'pi_reportdt'    => Carbon::parse($this->mntrData->report_date)->format('Y-m-d'),
            'pi_state_code'  => $this->mntrData->state_code,
            'pi_branch_code' => $this->mntrData->branch_code,
            'pi_officer_id'  => $this->mntrData->officer_id,
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
        return view('livewire.module.hr.index')->extends('layouts.main');
    }
}
