<?php

namespace App\Livewire\Module\Lantikan\Evaluator;

use App\Exports\EvaluatorSessionList;
use App\Models\BankOfficer;
use App\Models\MntrSession;
use App\Models\SettPymPmc;
use App\Models\SettUalRole;
use App\Models\SettUalUserHasRole;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use WireUi\Traits\Actions;

abstract class BasePmgi extends Component
{
    use Actions;

    public $stateCode;
    public $selectedDate;
    public $cardModal = false;
    public $pmgi;
    public $selection = [];
    public $selectedPym;
    public $selectedPmc;
    public $selectAll = false;
    public $datas;
    public $pymSelection;
    public $pmcSelection;
    public $pydData = [];

    protected function rules()
    {
        return [
            'selectedPym' => 'required',
            'selectedPmc' => [
                Rule::requiredIf(function () {
                    return $this->getPmgiLevel() == 'PM3';
                }),
                'different:selectedPym',
            ],
        ];
    }

    protected function messages()
    {
        return [
            'selectedPym.required' => 'PYM diperlukan.',
            'selectedPmc.required' => 'PMC diperlukan bagi PMGi 3.',
            'selectedPmc.different' => 'PMC tidak boleh sama dengan PYM.',
        ];
    }

    protected $listeners = ['refreshPmgi' => 'updateReportDate'];

    public function mount($currentDate)
    {
        $this->stateCode = auth()->user()->stateCode();

        $month = $currentDate->format('m');
        $year = $currentDate->format('Y');

        $this->updateReportDate($month, $year);
    }

    public function updateReportDate($month, $year)
    {
        $this->selectedDate = Carbon::create($year, $month, 1)->startOfMonth();
        $this->reset('selection');
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            // Select only the users with status 0 (not already in PMGI_SETT_PYM_PMC)
            $this->selection = $this->datas->where('status', 0)->pluck('USERID')->toArray();
        } else {
            $this->reset('selection');
        }
    }

    abstract protected function getPmgiLevel(): string;

    public function showSelection($pmgi)
    {
        if (count($this->selection) == 0) {
            $this->emptySelection();
        } else {
            $jawatanList = [];

            foreach ($this->selection as $selectedPyd) {
                $bankOfficer = BankOfficer::with('hrData')->whereOfficerId($selectedPyd)->first();

                if ($bankOfficer && $bankOfficer->hrData) {
                    $jawatanList[] = $bankOfficer->hrData->jawatan;
                }
            }

            if ($this->getPmgiLevel() == 'PM3') {
                    // Pass the unique jawatan to the getJawatan method
                    $this->getPymPmc();
                    $this->pmgi = $pmgi;
                    $this->cardModal = true;
            } else {
                $this->getPym();
                $this->pmgi = $pmgi;
                $this->cardModal = true;
            }
        }
    }

    public function getPym()
    {
        $urusetiaNegeriRoleId = DB::table('PMGI_SETT_UAL_ROLE')->where('name', 'URUSETIA NEGERI')->value('id');

        $this->pymSelection = DB::table('pmgi_fms_users as a')
                        ->distinct()
                        ->join('PMGI_FMS_BANK_OFFICERS as b', 'b.officer_id', '=', 'a.USERID')
                        ->join('PMGI_FMS_BRANCHES as C', 'C.branch_code', '=', 'b.branch_code')
                        ->leftJoin('PMGI_SETT_UAL_USER_HAS_ROLE as r', 'r.userid', '=', 'a.USERID')
                        ->select('a.USERID', 'b.officer_name', 'c.branch_name')
                        ->where('a.USERSTATUS', 1)
                        ->where(DB::raw('SUBSTRING(b.branch_code, 1, 2)'), $this->stateCode)
                        ->whereIn('b.officer_group', [5,12])
                        ->whereRaw('NOT EXISTS (SELECT 1 FROM PMGI_SETT_UAL_USER_HAS_ROLE ur WHERE ur.userid = a.USERID AND ur.role_id = ?)', [$urusetiaNegeriRoleId])
                        ->get()
                        ->toArray();
    }

    public function getPymPmc()
    {
        $urusetiaNegeriRoleId = DB::table('PMGI_SETT_UAL_ROLE')->where('name', 'URUSETIA NEGERI')->value('id');

        $this->pymSelection = DB::table('pmgi_fms_users as a')
                        ->distinct()
                        ->join('PMGI_FMS_BANK_OFFICERS as b', 'b.officer_id', '=', 'a.USERID')
                        ->join('PMGI_FMS_BRANCHES as C', 'C.branch_code', '=', 'b.branch_code')
                        ->leftJoin('PMGI_SETT_UAL_USER_HAS_ROLE as r', 'r.userid', '=', 'a.USERID')
                        ->select('a.USERID', 'b.officer_name', 'c.branch_name')
                        ->where('a.USERSTATUS', 1)
                        ->where(DB::raw('SUBSTRING(b.branch_code, 1, 2)'), $this->stateCode)
                        ->whereIn('b.officer_group', [5,12])
                        ->whereRaw('NOT EXISTS (SELECT 1 FROM PMGI_SETT_UAL_USER_HAS_ROLE ur WHERE ur.userid = a.USERID AND ur.role_id = ?)', [$urusetiaNegeriRoleId])
                        ->get()
                        ->toArray();

        $this->pmcSelection = DB::table('PMGI_JPOC as a')
                        ->distinct()
                        ->join('PMGI_FMS_BANK_OFFICERS as b', 'b.officer_id', '=', 'a.userid')
                        ->join('PMGI_FMS_BRANCHES as C', 'C.branch_code', '=', 'b.branch_code')
                        ->leftJoin('PMGI_SETT_UAL_USER_HAS_ROLE as r', 'r.userid', '=', 'a.userid')
                        ->select('a.userid', 'b.officer_name', 'c.branch_name')
                        ->whereRaw('NOT EXISTS (SELECT 1 FROM PMGI_SETT_UAL_USER_HAS_ROLE ur WHERE ur.userid = a.userid AND ur.role_id = ?)', [$urusetiaNegeriRoleId])
                        ->get()
                        ->toArray();
    }

    private function emptySelection()
    {
        $this->dialog()->error(
            $title = 'Ralat!',
            $description = 'Sila buat pilihan PYD dahulu sebelum teruskan dengan pemilihan.'
        );
    }

    public function save()
    {
        $this->validate();

        $this->processSelections();

        $pymEmail = $this->getPymEmailAddress();
        $pmcEmail = $this->getPmcEmailAddress();

        $this->sendEmails(
            $pymEmail,
            $pmcEmail,
        );

        $this->resetAfterSave();
    }

    private function generateExcelFile()
    {
        $pmgiValue = substr($this->getPmgiLevel(), -1);
        $filePath = 'exports/PYDLIST_' . $this->selectedPym . '_' . ($this->selectedPmc ? $this->selectedPmc : '') . '_' . now()->format('Ymd_His') . '.xlsx';
        Excel::store(new EvaluatorSessionList($pmgiValue, $this->selectedPym, $this->selectedPmc, $this->selection, $this->selectedDate), $filePath, 'public');
        return 'storage/' . $filePath;
    }

    private function processSelections()
    {
        foreach ($this->selection as $pyd) {
            $pydInfo = MntrSession::query()
                                    ->with('bankOfficer')
                                    ->whereOfficerId($pyd)
                                    ->whereDate('SESSION_DATE_START', $this->selectedDate)
                                    ->first();

            $sessionId = $this->generateSessionId($pydInfo, $pyd);

            $existingRecord = SettPymPmc::where('session_id', $sessionId)->first();

            $data = $this->prepareData($sessionId, $pydInfo, $pyd, $existingRecord);

            if ($existingRecord) {
                $existingRecord->update($data);
            } else {
                SettPymPmc::create($data);
            }

            $this->pydData[] = $pydInfo;
        }

        $this->giveRoles();
    }

    private function giveRoles()
    {
        $userPym = User::where('USERID', $this->selectedPym)->first();

        $pymRoleId = SettUalRole::where('name', 'PYM')->value('id');
        if ($pymRoleId) {
            $userPym->roles()->attach($pymRoleId);
            $userPym->load('roles');
        }

        if ($this->selectedPmc) {
            $userPmc = User::where('USERID', $this->selectedPmc)->first();

            $pmcRoleId = SettUalRole::where('name', 'PMC')->value('id');
            if ($pmcRoleId) {
                $userPmc->roles()->attach($pmcRoleId);
                $userPmc->load('roles');
            }
        }
    }

    private function generateSessionId($pydInfo, $pyd)
    {
        $datePart = now()->format('Ymd');
        $formattedDatePart = substr($datePart, 2, 2) . substr($datePart, 4, 2) . substr($datePart, 6, 2);
        return 'PMG' . substr($pydInfo->pmgi_level, -1) . $formattedDatePart . '/' . $pydInfo->pmgi_cycle . '/' . $pyd;
    }

    private function prepareData($sessionId, $pydInfo, $pyd, $existingRecord)
    {
        return [
            'session_id' => $sessionId,
            'pmgi_level' => $pydInfo->pmgi_level,
            'pyd_id' => $pyd,
            'pym_id' => $this->selectedPym,
            'pmc_id' => $this->selectedPmc,
            'created_by' => $existingRecord ? $existingRecord->created_by : auth()->user()->USERID,
            'created_at' => $existingRecord ? $existingRecord->created_at : now(),
            'updated_by' => auth()->user()->USERID,
            'updated_at' => now(),
            'report_date' => $pydInfo->report_date,
            'branch_code' => $pydInfo->branch_code,
        ];
    }

    private function getPymEmailAddress()
    {
        return BankOfficer::whereOfficerId($this->selectedPym)->value('email');
    }

    private function getPmcEmailAddress()
    {
        return $this->selectedPmc ? BankOfficer::whereOfficerId($this->selectedPmc)->value('email') : null;
    }

    private function sendEmails($pymEmail, $pmcEmail)
    {
        foreach ($this->pydData as $pydInfo) {
            $pmgiLevel = substr($this->getPmgiLevel(), -1);
            $lastDate = Carbon::now()->day(25)->format('Y-m-d');

            $procedureName = 'dbo.UP_PMGI_EMAIL_APT_PYMPMC';

            // Send to PYM
            if ($pymEmail) {
                $this->dispatchEmail($procedureName, [
                    'email_setting_no'              => 101,
                    'parm_receipients'              => $pymEmail,
                    'parm_copy_recipients'          => null,
                    'parm_blind_copy_recipients'    => null,
                    'JenisPegawai'                  => 'Pegawai Yang Menilai',
                    'SesiPMGi'                      => $pmgiLevel,
                    'PC_PP'                         => $pydInfo->bankOfficer->officer_name,
                    'TarikhPelaksanaan'             => $lastDate,
                    'PengurusNegeri_JPOC'           => 'Jabatan Pemantauan dan Operasi Cawangan',
                ]);
            }

            // Send to PMC
            if ($pmcEmail) {
                $this->dispatchEmail($procedureName, [
                    'email_setting_no'              => 101,
                    'parm_receipients'              => $pmcEmail,
                    'parm_copy_recipients'          => null,
                    'parm_blind_copy_recipients'    => null,
                    'JenisPegawai'                  => 'Pegawai Mudah Cara',
                    'SesiPMGi'                      => $pmgiLevel,
                    'PC_PP'                         => $pydInfo->bankOfficer->officer_name,
                    'TarikhPelaksanaan'             => $lastDate,
                    'PengurusNegeri_JPOC'           => 'Jabatan Pemantauan dan Operasi Cawangan',
                ]);
            }
        }
    }

    public function dispatchEmail($procedureName, $bindings)
    {
        DB::executeProcedure($procedureName, $bindings);
    }

    private function resetAfterSave()
    {
        $this->cardModal = false;
        $this->reset('selection', 'selectedPym', 'selectedPmc');

        $this->dialog()->success(
            $title = 'Berjaya disimpan',
            $description = 'Lantikan PYM dan PMC berjaya disimpan.'
        );
    }

    public function render()
    {
        $this->datas = DB::table('pmgi_mntr_session as m')
                            ->join('pmgi_fms_users as a', 'm.officer_id', '=', 'a.USERID')
                            ->join('pmgi_fms_branches as b', 'm.branch_code', '=', 'b.branch_code')
                            ->join('pmgi_fms_bank_officers as c', 'c.officer_id', '=', 'a.USERID')
                            ->join('pmgi_hrd_officer as d', 'd.no_pekerja', '=', 'c.staffno')
                            ->leftJoin('pmgi_sett_pym_pmc as e', function ($join) {
                                $join->on('e.pyd_id', '=', 'm.officer_id')
                                    ->whereDate('e.report_date', $this->selectedDate->copy()->subMonthNoOverflow()->endOfMonth());
                            })
                            ->leftJoin('pmgi_fms_users as pym_user', 'e.pym_id', '=', 'pym_user.USERID')
                            ->leftJoin('pmgi_fms_users as pmc_user', 'e.pmc_id', '=', 'pmc_user.USERID')
                            ->select(
                                'a.USERID',
                                'a.USERNAME',
                                'm.branch_code',
                                'd.jawatan',
                                'd.gelaran',
                                'b.branch_name',
                                'm.pmgi_cycle',
                                'm.pmgi_level',
                                DB::raw('CASE WHEN e.pyd_id IS NULL THEN 0 ELSE 1 END as status'),
                                'e.pym_id',
                                'pym_user.USERNAME as pym_name',
                                'e.pmc_id',
                                'pmc_user.USERNAME as pmc_name'
                            )
                            ->whereDate('session_date_start', $this->selectedDate)
                            ->where('m.state_code', $this->stateCode)
                            ->where('m.pmgi_level', $this->getPmgiLevel())
                            ->orderBy('b.branch_name', 'asc')
                            ->get();

        $pmgiValue = substr($this->getPmgiLevel(), -1);

        return view('livewire.module.lantikan.evaluator.base-pmgi', [
            'pmgiValue' => $pmgiValue,
            'datas' => $this->datas,
            'pym' => $this->pymSelection,
            'pmc' => $this->pmcSelection,
        ]);
    }
}
