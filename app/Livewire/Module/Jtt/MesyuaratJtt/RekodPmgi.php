<?php

namespace App\Livewire\Module\Jtt\MesyuaratJtt;

use App\Jobs\CleanupTemporaryFiles;
use App\Jobs\SendJttHrEmail;
use App\Models\BankOfficer;
use App\Models\JttSessionInfo;
use App\Models\JttSessionParticipant;
use App\Models\MntrSession;
use App\Models\SessionInfo;
use App\Models\SessionJttPydInfo;
use App\Models\SettUalRole;
use App\Models\SettUalUserHasRole;
use App\Services\HtmlToImageService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use PDO;

use WireUi\Traits\Actions;

class RekodPmgi extends Component
{
    use Actions;

    private $htmlToImageService;

    public $detailsModal = false;
    public $userId;
    public $reportDate;
    public $sessionId;
    public $pmgiSessionId;
    public $cycle;
    public $sessionInfo;
    public $staffNo;
    public $state;
    public $branch;
    public $staffName;
    public $staffIc;
    public $pmgiLevel;
    public $pmgiCycle;
    public $pmgiData;
    public $result;
    public $mthDelay = 0;
    public $comment;

    public function __construct()
    {
        $this->htmlToImageService = new HtmlToImageService();
    }

    protected function rules()
    {
        return [
            'result' => 'required',
            'mthDelay' => [
                Rule::requiredIf(function () {
                    return $this->result == "Diberi Tempoh";
                }),
            ],
            'comment' => 'required',
        ];
    }

    protected function messages()
    {
        return [
            'result.required' => 'Keputusan penilaian diperlukan.',
            'mthDelay.required' => 'Sila isikan dari 1-6 bulan.',
            'comment.required' => 'Sila isikan ulasan.',
        ];
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

    public function toggleDetail($sessionId)
    {
        $this->pmgiSessionId = str_replace('/', '-', $sessionId);
        $this->detailsModal = true;
    }

    public function getPmgiData($cycle)
    {
        // Use a join to filter by both report_date and officer_id
        $mntrData = DB::table('PMGI_MNTR_SESSION as ms')
                    ->join('PMGI_SETT_PYM_PMC as sp', function ($join) {
                        $join->on('ms.report_date', '=', 'sp.report_date')
                            ->where('sp.pyd_id', '=', $this->userId); // Match on both report_date and officer_id (pyd_id)
                    })
                    ->where('ms.officer_id', '=', $this->userId)
                    ->where('ms.pmgi_cycle', '=', $cycle)
                    ->whereNotIn('ms.pmgi_level', ['MN1', 'MN2', 'MN3', 'MN4', 'MT2'])
                    ->orderBy('ms.seq_no', 'ASC')
                    ->select(
                        'ms.seq_no as seq',
                        'ms.pmgi_level as lvl',
                        'sp.pym_id', // We’ll use this to retrieve the pym's username
                        'sp.pmc_id', // We’ll use this to retrieve the pmc's username (if needed)
                        'sp.session_id',
                        'sp.created_at as date_session',
                        'ms.pmgi_result'
                    )
                    ->get();

        // Transform the data
        $resultMapping = [
            'CP1' => 'SELESAI DILAKSANAKAN',
            'CP2' => 'SELESAI DILAKSANAKAN',
            'PEX' => 'DISYORKAN KELUAR TANPA SYARAT',
            'EXC' => 'DISYORKAN KELUAR DENGAN SYARAT',
            'EXP' => 'DITANGGUHKAN',
            'NEX' => 'DIHANTAR KE SESI TIMBANG TARA',
            'EX1' => 'KELUAR SENARAI',
            'PDQ' => 'DIBERI TEMPOH',
            'DQ1' => 'DOMESTIC INQUIRY',
            'EXL' => 'KELUAR SENARAI',
            'DQ2' => 'DOMESTIC INQUIRY',
            'PDM' => 'DITAMATKAN PERKHIDMATAN',
            'NDM' => 'PERKHIDMATAN DISAMBUNG',
        ];

        $this->pmgiData = $mntrData->map(function ($data) use ($resultMapping) {
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
            ];

            // Add 'pmc' field conditionally if pmgi_level is 'PM3'
            if ($data->lvl == 'PM3' && $pmc) {
                $result['pmc'] = $pmc->officer_name;
            }

            return $result;
        });

        // dd($this->pmgiData); // Check the resulting collection
    }

    public function submit()
    {
        $this->validate();

        // save participant session
        JttSessionParticipant::create([
            'session_id' => $this->sessionId,
            'user_id' => $this->userId,
            'pmgi_level' => $this->pmgiLevel,
            'report_date' => $this->reportDate
        ]);

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
            } elseif($this->result == 'Keluar Senarai') {
                $pmgiResult = 'EX1';
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
            'pi_wait_period' => $this->mthDelay,
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
        return view('livewire.module.jtt.mesyuarat-jtt.rekod-pmgi');
    }
}
