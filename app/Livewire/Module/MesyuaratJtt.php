<?php

namespace App\Livewire\Module;

use App\Jobs\CleanupTemporaryFiles;
use App\Jobs\SendJttHrEmail;
use App\Models\BankOfficer;
use App\Models\JttSessionInfo;
use App\Models\MntrSession;
use App\Models\SessionInfo;
use App\Models\SessionJttPydInfo;
use App\Models\SettUalRole;
use App\Models\SettUalUserHasRole;
use App\Services\HtmlToImageService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PDO;
use WireUi\Traits\Actions;

class MesyuaratJtt extends Component
{
    use Actions;

    private $htmlToImageService;

    public $sessionId;
    public $userId;
    public $reportDate;
    public $sessionInfo;
    public $staffNo;
    public $state;
    public $branch;
    public $staffName;
    public $staffIc;
    public $pmgiLevel;
    public $pmgiData;
    public $result;
    public $mthDelay;
    public $comment;

    public function __construct()
    {
        $this->htmlToImageService = new HtmlToImageService();
    }

    public function mount()
    {
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

        $this->sessionInfo = JttSessionInfo::with('venueInfo')->whereSessionId($this->sessionId)->first();
        $this->staffNo = $data->user->bankOfficer->staffno;
        $this->state = $data->state->description;
        $this->branch = $data->branch->branch_name;
        $this->staffName = $data->user->username;
        $this->staffIc = $data->user->bankOfficer->nokp;
        $this->pmgiLevel = $data->pmgi_level;
        $this->getPmgiData($data->pmgi_cycle);
    }

    public function getPmgiData($cycle)
    {
        $mntrData = MntrSession::whereOfficerId($this->userId)
                                ->wherePmgiCycle($cycle)
                                ->whereNotIn('pmgi_level', ['MN1', 'MN2', 'MN3', 'MN4', 'MT2'])
                                ->orderBy('seq_no', 'ASC')
                                ->get();

        // Initialize a collection to hold the transformed data
        $this->pmgiData = $mntrData->map(function ($data) {
            // Ensure settPymPmc relationship is loaded and exists
            if ($data->settPymPmc) {
                // Map pmgi_result codes to their descriptions
                $resultMapping = [
                    'CP1' => 'SELESAI DILAKSANAKAN',
                    'CP2' => 'SELESAI DILAKSANAKAN',
                    'PEX' => 'DISYORKAN KELUAR TANPA SYARAT',
                    'EXC' => 'DISYORKAN KELUAR DENGAN SYARAT',
                    'EXP' => 'DITANGGUHKAN',
                    'NEX' => 'DIHANTAR KE SESI TIMBANG TARA',
                    'PDQ' => 'DIBERI TEMPOH'
                ];

                // Get the corresponding description or a default message
                $pmgiResult = $resultMapping[$data->pmgi_result] ?? 'RESULT UNKNOWN';

                // Initialize the array with common fields
                $result = [
                    'seq' => $data->seq_no,
                    'lvl' => $data->pmgi_level,
                    'pym' => $data->settPymPmc->pym->username,
                    'date_session' => $data->settPymPmc->created_at->format('d/m/Y'),
                    'result' => $pmgiResult,
                ];

                // Add 'pmc' field conditionally if pmgi_level is 'PM3'
                if ($data->pmgi_level == 'PM3') {
                    $result['pmc'] = $data->settPymPmc->pmc->username;
                }

                return $result;
            }

            // Return null or any default value if settPymPmc is missing
            return null;
        })->filter();
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
            // sent email to HR if DI
            if($this->result == 'Domestic Inquiry (DI)'){
                $this->sendEmailToHr();
            }

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

        $procedureName = 'dbo.UP_PMGI_UPD_MNTR_SESSION';

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

    private function sendEmailToHr()
    {
        $path = $this->generateImageFromHtml();
        $emails = $this->getHrEmail();

        foreach ($emails as $email) {
            $this->sendEmail($email, $path['image'], $path['html']);
        }
    }

    private function generateImageFromHtml()
    {
        return $this->htmlToImageService->generate(
            'emails.di_hr',
            [
                'dateMeeting' => now()->format('d/m/Y'),
                'venue' => $this->sessionInfo->venueInfo->room_name,
                'pydName' => $this->staffName,
                'pydIc' => $this->staffIc,
                'pydState' => $this->state,
                'pydBranch' => $this->branch,
            ],
            'emails/hr/',
            "email_hr_{$this->userId}"
        );
    }

    private function getHrEmail(): array
    {
        //get role id of HR
        $roleId = SettUalRole::whereName('HR')->value('id');

        // get all user that has HR role
        $hrUsers = SettUalUserHasRole::whereRoleId($roleId)->get();

        // get email for all the hr users
        $hrEmails = [];
        foreach ($hrUsers as $user) {
            $hrEmails[] = BankOfficer::whereOfficerId($user->userid)->value('email');
        }

        return $hrEmails;
    }

    private function sendEmail($email, $imagePath, $htmlPath)
    {
        $jobs = [];

        if ($email) {
            $jobs[] = new SendJttHrEmail($email, $imagePath);
        }

        // Chain the cleanup job after the email jobs
        $jobs[] = new CleanupTemporaryFiles([$imagePath], [$htmlPath]);

        // Dispatch the jobs as a chain
        Bus::chain($jobs)->dispatch();
    }

    public function render()
    {
        return view('livewire.module.mesyuarat-jtt', [])->extends('layouts.main');
    }
}
