<?php

namespace App\Livewire;

use App\Jobs\CleanupTemporaryFiles;
use App\Jobs\SendKeputusanPmgiPyd;
use App\Models\MntrSession;
use App\Models\SessionInfo;
use App\Models\SessionPmcInfo;
use App\Models\SessionPydInfo;
use App\Models\SessionPymInfo;
use App\Models\SettPymPmc;
use App\Services\HtmlToImageService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Bus;
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
    public $pmgiType;
    public $pydId;
    public $hasRedirected = false;
    private $htmlToImageService;

    public function __construct()
    {
        $this->htmlToImageService = new HtmlToImageService();
    }

    public function mount()
    {
        $this->showError();
        $this->fetchQueryString();
        $this->setText();
        $this->pmgiLevel = substr($this->sessionId, 3, 1);
        $this->pmgiType = substr($this->sessionId, 0, 2);
        $this->pydId = substr($this->sessionId, 11);
    }

    protected function showError()
    {
        // check flash error from middleware
        if (session()->has('flash_error')) {
            $this->dialog()->error(
                $title = 'Perhatian.',
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

                    // send email to PYD
                    $this->sendEmailToPyd();
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
                    
                    // send email to PYD
                    $this->sendEmailToPyd();
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

        $procedureName = 'dbo.UP_PMGI_UPD_MNTR_SESSION';

        $bindings = [
            'pi_reportdt'    => $pmgiResult == 'NEX' ? Carbon::parse($data->report_date)->addMonthNoOverflow()->endOfMonth()->format('Y-m-d') : Carbon::parse($data->report_date)->format('Y-m-d'),
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

    private function sendEmailToPyd()
    {
        $setting = SettPymPmc::whereSessionId($this->sessionId)->first();
        $pyd_data = MntrSession::with('user', 'state', 'branch', 'bankOfficer')
                            ->whereOfficerId($this->pydId)
                            ->whereDate('report_date', $setting->report_date)
                            ->first();

        $path = $this->generateImageFromHtml($pyd_data);
        $email = $pyd_data->bankOfficer?->email;

        $this->sendEmail($email, $path['image'], $path['html']);
    }

    private function generateImageFromHtml($data)
    {
        $pmgi_description = substr($data->pmgi_level, 0, 2) == 'PM' ? 'PMGI' : (substr($data->pmgi_level, 0, 2) == 'JT' ? 'JKPI' : (substr($data->pmgi_level, 0, 2) == 'HR' ? 'HR' : 'undefined'));
        return $this->htmlToImageService->generate(
            'emails.keputusan_pmgi',
            [
                'pmgi_session_date' => now()->format('d/m/Y'),
                'pyd_name' => $data->bankOfficer?->officer_name,
                'pyd_ic' => $data->bankOfficer?->nokp,
                'pyd_state' => $data->state->description,
                'pyd_branch' => $data->branch->branch_name,
                'pmgi_type' => $pmgi_description,
                'pmgi_level' => $this->pmgiLevel,
            ],
            'emails/pyd/',
            "email_pyd_{$this->pydId}"
        );
    }

    private function sendEmail($email, $imagePath, $htmlPath)
    {
        $jobs = [];

        if ($email) {
            $jobs[] = new SendKeputusanPmgiPyd($email, $imagePath, $htmlPath);
        }

        // Chain the cleanup job after the email jobs
        $jobs[] = new CleanupTemporaryFiles([$imagePath], [$htmlPath]);

        // Dispatch the jobs as a chain
        Bus::chain($jobs)->dispatch();
    }

    public function render()
    {
        return view('livewire.loading-perakuan')->extends('layouts.main');
    }
}
