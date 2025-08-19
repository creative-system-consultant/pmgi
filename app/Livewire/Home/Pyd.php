<?php

namespace App\Livewire\Home;

use App\Models\PmgiSummMiaAppl;
use App\Models\PmgiSummPembiayaanProduk;
use App\Models\PmgiSummRescheduleInfo;
use App\Models\PmgiSummWilma;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Pyd extends Component
{
    public $userId;
    public $user;
    public $data;
    public $pmgiLevels;
    public $pmgiResults;
    public $penjadualanSemula;
    public $mia;
    public $wilma;
    public $pembiayaan;
    public $username;
    public $staffno;
    public $jawatan;
    public $stateName;
    public $branchName;
    public $tarikhLantikan;
    public $tempohBerkhidmat;

    public function mount()
    {
        if ($this->userId) {
            $this->user = $this->userId;
        } else {
            $authUser = auth()->user();
            $this->user = $authUser->USERID;
        }

        $authUser = auth()->user();

        $this->data = User::find($this->user);
        $this->username = $this->data->USERNAME;
        $this->staffno = $this->data->staffNo();
        $this->jawatan = $this->data->bankOfficer->hrData?->jawatan;
        $this->stateName = $this->data->stateName();
        $this->branchName = $this->data->branchName();
        $this->tarikhLantikan = Carbon::parse($this->data->bankOfficer->hrData?->tarikh_lantikan)->translatedFormat('d F Y');
        $tempoh = $this->data->bankOfficer->hrData?->tempoh_penempatan_semasa;
        $this->tempohBerkhidmat = str_replace(['Y', 'M', 'D'], [' Tahun ', ' Bulan ', ' Hari'], $tempoh);

        $this->penjadualanSemula = (function () use ($authUser) {
            $maxDate = PmgiSummRescheduleInfo::max('report_date');
            return PmgiSummRescheduleInfo::where('report_date', $maxDate)
                ->where('branch_code', $authUser->branchCode())
                ->first();
        })();

        $this->mia = (function () use ($authUser) {
            $maxDate = PmgiSummMiaAppl::max('report_date');
            return PmgiSummMiaAppl::where('report_date', $maxDate)
                ->where('branch_code', $authUser->branchCode())
                ->first();
        })();

        $this->wilma = (function () use ($authUser) {
            $maxDate = PmgiSummWilma::max('report_date');
            return PmgiSummWilma::where('report_date', $maxDate)
                ->where('branch_code', $authUser->branchCode())
                ->first();
        })();

        $this->pembiayaan = (function () use ($authUser) {
            $maxDate = PmgiSummPembiayaanProduk::max('report_date');
            return PmgiSummPembiayaanProduk::where('report_date', $maxDate)
                ->where('branch_code', $authUser->branchCode())
                ->get();
        })();

        $this->pmgiLevels = [
            'PM1' => 'PMG-i (1)',
            'PM2' => 'PMG-i (2)',
            'PM3' => 'PMG-i (3)',
            'JT1' => 'JKPI (1)',
            'JT2' => 'JKPI (2)',
        ];

        $this->pmgiResults = [
            'PM3' => [
                'NEX' => ['text' => 'TIDAK SYOR<br>KELUAR', 'class' => 'bg-yellow-100 text-yellow-800'],
                'EXP' => ['text' => 'TANGGUH', 'class' => 'bg-yellow-100 text-yellow-800'],
                'EXC' => ['text' => 'KELUAR<br>BERSYARAT', 'class' => 'bg-yellow-100 text-yellow-800'],
                'PEX' => ['text' => 'KELUAR TANPA<br>SYARAT', 'class' => 'bg-green-100 text-green-800'],
            ],
            'JT1' => [
                'EX1' => ['text' => 'KELUAR<br>SENARAI', 'class' => 'bg-green-100 text-green-800'],
                'PDQ' => ['text' => 'DIBERI<br>TEMPOH', 'class' => 'bg-red-100 text-red-800'],
                'DQ1' => ['text' => 'DOMESTIC<br>INQUIRY', 'class' => 'bg-red-100 text-red-800'],
            ],
            'JT2' => [
                'EXL' => ['text' => 'KELUAR<br>SENARAI', 'class' => 'bg-green-100 text-green-800'],
                'DQ2' => ['text' => 'DOMESTIC<br>INQUIRY', 'class' => 'bg-red-100 text-red-800'],
            ],
            'HRD' => [
                'NDM' => ['text' => 'TIDAK<br>DIBERHENTIKAN', 'class' => 'bg-green-100 text-green-800'],
                'PDM' => ['text' => 'DIBERHENTIKAN', 'class' => 'bg-red-100 text-red-800'],
            ],
        ];
    }

    public function render()
    {
        $officerData = $this->getOfficerData('desc', 8);
        $officerDataAsc = $officerData->reverse()->values()->toArray();

        return view('livewire.home.pyd', [
            'officerDatas' => $officerData,
            'officerDatasAsc' => $officerDataAsc
        ]);
    }

    public function getOfficerData(string $order, int $limit)
    {
        $officerId = $this->user;

        // Get summary data from PMGI_SUMM_MTH_OFFICER
        $summaryData = DB::table('PMGI_SUMM_MTH_OFFICER as a')
            ->where('a.officer_id', $officerId)
            ->whereIn('a.incl_pmgi_flag', ['B', 'D'])
            ->select(
                'a.report_date',
                'a.bil_selia',
                'a.bil_dapat_kutip',
                'a.rm_patut_kutip',
                'a.rm_dapat_kutip',
                'a.rm_dapat_kutip_pts',
                'a.bil_lawat',
                'a.bil_lawat_pts',
                'a.rm_dapat_kutip_nilai_pts',
                'a.bil_dapat_kutip_nilai_pts',
            )
            ->orderBy('a.report_date', $order)
            ->take($limit)
            ->get();

        // Format the report_date field
        $summaryData->transform(function ($item) {
            $item->report_date = \Carbon\Carbon::parse($item->report_date)->format('Y-m');
            return $item;
        });

        // Get session data from PMGI_MNTR_SESSION for the same officer
        $sessionData = DB::table('PMGI_MNTR_SESSION as b')
            ->where('b.officer_id', $officerId)
            ->select(
                'b.pmgi_result',
                'b.pmgi_level',
                DB::raw("FORMAT(b.session_date_start, 'yyyy-MM') as session_start_month"),
                DB::raw("FORMAT(b.session_date_end, 'yyyy-MM') as session_end_month"),
                'b.wait_period'
            )
            ->get();

        return $this->mergeData($summaryData, $sessionData);
    }

    private function mergeData($summaryData, $sessionData)
    {
        // Prepare a map for session data based on the month/year
        $sessionMap = [];
        foreach ($sessionData as $session) {
            $sessionMap[$session->session_start_month] = $session;
            $sessionMap[$session->session_end_month] = $session;
        }

        $monitoringPeriodEnd = null;  // Initialize the monitoring end date

        foreach ($summaryData as $summary) {
            $monthYear = Carbon::parse($summary->report_date)->format('Y-m');

            if (isset($sessionMap[$monthYear])) {
                $session = $sessionMap[$monthYear];
                $summary->pmgi_result = $session->pmgi_result;
                $summary->pmgi_level = $session->pmgi_level;
                $summary->wait_period = $session->wait_period;

                // Set the monitoring period end if a wait period exists
                if ($session->wait_period) {
                    $monitoringPeriodEnd = Carbon::parse($summary->report_date)->addMonths((int) $session->wait_period);
                }
            } else {
                $summary->pmgi_result = null;
                $summary->pmgi_level = null;
                $summary->wait_period = null;
            }

            // Set the monitoring flag for entries within the monitoring period
            if ($monitoringPeriodEnd && Carbon::parse($summary->report_date)->lessThanOrEqualTo($monitoringPeriodEnd)) {
                $summary->is_monitoring_period = true;
            } else {
                $summary->is_monitoring_period = false;
            }

            // Format the report_date to your desired format
            $summary->report_date = Carbon::parse($summary->report_date)->translatedFormat('M-y');
        }

        return $summaryData;
    }
}
