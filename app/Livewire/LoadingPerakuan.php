<?php

namespace App\Livewire;

use App\Models\MntrSession;
use App\Models\SessionInfo;
use App\Models\SessionPmcInfo;
use App\Models\SessionPydInfo;
use App\Models\SessionPymInfo;
use App\Models\SettPymPmc;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PDO;
use WireUi\Traits\Actions;

class LoadingPerakuan extends Component
{
    use Actions;

    public $sessionId;
    public $title;
    public $subtitle;
    public $pmgiLevel;
    public $hasRedirected = false;

    public function mount()
    {
        $this->showError();
        $this->fetchQueryString();
        $this->setText();
        $this->pmgiLevel = substr($this->sessionId, 3, 1);
    }

    protected function showError()
    {
        // check flash error from middleware
        if (session()->has('flash_error')) {
            $this->dialog()->error(
                $title = 'Ralat!',
                $description = session('flash_error')
            );
        }
    }

    protected function fetchQueryString()
    {
        $this->sessionId = str_replace('-', '/', request()->query('session_id'));
    }

    protected function setText()
    {
        $this->title = 'Sila tunggu semua peserta membuat perakuan.';
        $this->subtitle = 'Halaman ini akan dialihkan secara automatik apabila semua peserta selesai membuat perakuan.';
    }

    public function checkRecord()
    {
        // If already redirected, stop further processing
        if ($this->hasRedirected) {
            return;
        }

        if($this->pmgiLevel != 3) {
            // Check if a date signed exists in the table with the session ID
            $pydSigned = SessionPydInfo::where('session_id', $this->sessionId)->value('date_signed');
            $pymSigned = SessionPymInfo::where('session_id', $this->sessionId)->value('date_signed');

            if ($pydSigned && $pymSigned) {
                SessionInfo::whereSessionId($this->sessionId)->update(['status' => 1]);
                SettPymPmc::whereSessionId($this->sessionId)->update(['status' => 1]);

                $resultSp = $this->runSp();

                // Redirect to the next page or do whatever action you need
                if (substr($resultSp, 0, 1) == '0') {
                    $this->hasRedirected = true;
                    return redirect()->route('home')->with('flash_success', 'Sesi selesai dilaksanakan.');
                } else {
                    $this->dialog()->error(
                        $title = 'Ralat!',
                        $description = "Masalah Server."
                    );
                }
            }
        } else {
            // Check if a date signed exists in the table with the session ID
            $pydSigned = SessionPydInfo::where('session_id', $this->sessionId)->value('date_signed');
            $pymSigned = SessionPymInfo::where('session_id', $this->sessionId)->value('date_signed');
            $pmcSigned = SessionPmcInfo::where('session_id', $this->sessionId)->value('date_signed');

            if ($pydSigned && $pymSigned && $pmcSigned) {
                SessionInfo::whereSessionId($this->sessionId)->update(['status' => 1]);
                SettPymPmc::whereSessionId($this->sessionId)->update(['status' => 1]);

                $resultSp = $this->runSp();

                // Redirect to the next page or do whatever action you need
                if (substr($resultSp, 0, 1) == '0') {
                    $this->hasRedirected = true;
                    return redirect()->route('home')->with('flash_success', 'Sesi selesai dilaksanakan.');
                } else {
                    $this->dialog()->error(
                        $title = 'Ralat!',
                        $description = 'Masalah Server.'
                    );
                }
            }
        }
    }

    public function runSp()
    {
        if ($this->pmgiLevel == 3) {
            $pmcData = SessionPmcInfo::whereSessionId($this->sessionId)->first();
        }

        if($this->pmgiLevel == 1) {
            $pmgiResult = 'CP1';
        } elseif($this->pmgiLevel == 2) {
            $pmgiResult = 'CP2';
        } elseif($this->pmgiLevel == 3 && $pmcData->exit_flag == 1 && $pmcData->exit_type_flag == 1) { // syor keluar tanpa syarat
            $pmgiResult = 'PEX';
        } elseif($this->pmgiLevel == 3 && $pmcData->exit_flag == 1  && $pmcData->exit_type_flag == 2) { // syok keluar bersyarat
            $pmgiResult = 'EXC';
        } elseif($this->pmgiLevel == 3 && $pmcData->exit_flag == 1 && $pmcData->exit_type_flag == 3) { // syor keluar penangguhan
            $pmgiResult = 'EXP';
        } else {  // x syor keluar
            $pmgiResult = 'NEX';
        }

        $setting = SettPymPmc::whereSessionId($this->sessionId)->first();
        $data = MntrSession::whereOfficerId($setting->pyd_id)
                            ->whereDate('report_date', $setting->report_date)
                            ->first();

        $output = '';

        $procedureName = 'dbo.UP_PMGI_MNTR_SESSION';

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
        return view('livewire.loading-perakuan')->extends('layouts.main');
    }
}
