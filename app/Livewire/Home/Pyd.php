<?php

namespace App\Livewire\Home;

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
            $this->user = auth()->user()->userid;
        }

        $this->data = User::find($this->user);
        $this->username = $this->data->username;
        $this->staffno = $this->data->staffNo();
        $this->jawatan = $this->data->bankOfficer->hrData->jawatan;
        $this->stateName = $this->data->stateName();
        $this->branchName = $this->data->branchName();
        $this->tarikhLantikan = Carbon::parse($this->data->bankOfficer->hrData->tarikh_lantikan)->translatedFormat('d F Y');
        $tempoh = $this->data->bankOfficer->hrData->tempoh_penempatan_semasa;
        $this->tempohBerkhidmat = str_replace(['Y', 'M', 'D'], [' Tahun ', ' Bulan ', ' Hari'], $tempoh);

        $this->penjadualanSemula = Cache::remember('penjadualan_semula_' . auth()->user()->branchCode(), 480, function () {
            return DB::table('RESCHEDULE_INFO2 as rs')
                ->join('account_master as m', 'rs.accountno', '=', 'm.account_no')
                ->where('activestatus', '>=', 0)
                ->where('branch_code', auth()->user()->branchCode())
                ->selectRaw('
                            count(*) as terima,
                            sum(case when nvl(branchapproval,0) = 1 then 1 else 0 end) as lulus,
                            sum(case when nvl(branchapproval,0) = 9 then 1 else 0 end) as tolak,
                            sum(case when nvl(branchapproval,0) in (0,2) then 1 else 0 end) as baki,
                            sum(case when nvl(branchapproval,0) = 8 then 1 else 0 end) as batal,
                            sum(case when nvl(rs.cr_acct_flag,0) = 1 then 1 else 0 end) as jana,
                            sum(apprvlimit) as jumterima,
                            sum(case when nvl(branchapproval,0) = 1 then apprvlimit else 0 end) as jumlulus,
                            sum(case when nvl(branchapproval,0) = 9 then apprvlimit else 0 end) as jumtolak,
                            sum(case when nvl(branchapproval,0) in (0,2) then apprvlimit else 0 end) as jumbaki,
                            sum(case when nvl(branchapproval,0) = 8 then apprvlimit else 0 end) as jumbatal
                        ')
                ->first();
        });

        $this->mia = Cache::remember('mia_' . auth()->user()->branchCode(), 480, function () {
            return DB::table('mia_appl_list as ma')
                ->join('account_master as m', 'ma.custid', '=', 'm.cust_id')
                ->where('branch_code', auth()->user()->branchCode())
                ->selectRaw('
                            count(*) as jumlah,
                            sum(case when ma.status = 1 then 1 else 0 end) as lulus,
                            sum(case when ma.status = 0 then 1 else 0 end) as proses,
                            sum(case when ma.status = -1 then 1 else 0 end) as tolak,
                            sum(case when ma.status = -2 then 1 else 0 end) as dikembalikan
                        ')
                ->first();
        });

        $this->wilma = Cache::remember('wilma_' . auth()->user()->branchCode(), 480, function () {
            return DB::select("
                select
                    bilA1 as bila1,
                    bilA2 as bila2,
                    bilA3 as bila3,
                    bilb1,
                    bilB2 as bilb2,
                    bilC1 as bilc1,
                    bilC2 as bilc2,
                    bilD as bild,
                    (bilA1+bilA2+bilA3+bilb1+bilB2+bilC1+bilC2+bilD) as jumlah,
                    bilNPF as bilnpf,
                    round((bilNPF/bilALL)*100,2) as pctnpf,
                    round((JUMNPF/JUMALL)*100,2) as pctjumnpf
                from
                (
                    select
                        SUM((CASE WHEN p.npl_category = 'A1'  THEN 1 ELSE 0 END)) AS bilA1,
                        SUM((CASE WHEN p.npl_category = 'A2'  THEN 1 ELSE 0 END)) AS bilA2,
                        SUM((CASE WHEN p.npl_category = 'A3'  THEN 1 ELSE 0 END)) AS bilA3,
                        SUM((CASE WHEN p.npl_category = 'B1'  THEN 1 ELSE 0 END)) AS bilb1,
                        SUM((CASE WHEN p.npl_category = 'B2'  THEN 1 ELSE 0 END)) AS bilB2,
                        SUM((CASE WHEN p.npl_category = 'C1'  THEN 1 ELSE 0 END)) AS bilC1,
                        SUM((CASE WHEN p.npl_category = 'C2'  THEN 1 ELSE 0 END)) AS bilC2,
                        SUM((CASE WHEN p.npl_category = 'D'  THEN 1 ELSE 0 END)) AS bilD,
                        SUM((CASE WHEN p.npl_category in ('B1','B2','C1','C2','D')  THEN 1 ELSE 0 END)) AS bilNPF,
                        SUM((CASE WHEN p.npl_category in ('A1','A2','A3','B1','B2','C1','C2','D')  THEN 1 ELSE 0 END)) AS bilALL,
                        SUM((CASE WHEN p.npl_category in ('B1','B2','C1','C2','D') THEN p.bal_outstanding ELSE 0 END)) AS JUMNPF,
                        SUM((CASE WHEN p.npl_category in ('A1','A2','A3','B1','B2','C1','C2','D') THEN p.bal_outstanding ELSE 0 END)) AS JUMALL
                    from account_master m
                    inner join account_position p on m.account_no = p.account_no
                    where m.account_status  not in  (1,2,6,9,13,15,16,19)
                    AND    nvl(m.account_status2,0) <> '13'
                    AND    nvl(m.seliaanowner, 'BRN') = 'BRN'
                    AND    m.branch_code = ?
                )
            ", [auth()->user()->branchCode()])[0] ?? null;
        });

        $currentDate = now()->format('d M Y');

        $this->pembiayaan = Cache::remember('pembiayaan_' . auth()->user()->branchCode() . '_' . $currentDate, 480, function () use ($currentDate) {
            $pembiayanData = DB::select("
                                select nvl(uf_decode_prodcatg(product_catg), 0) as product_category,
                                    sum(bil) as bilakaun,
                                    sum(bilpeminjam) as bil_peminjam,
                                    sum(amt) as jumlah_pembiayaan
                                from (
                                    SELECT count(*) AS bil,
                                        count(distinct m.cust_id) as bilpeminjam,
                                        nvl(sum(m.approved_limit), 0) AS amt,
                                        nvl(SUBSTR(UF_GET_PRODUCT_CATG(m.PRODUCT_CODE, m.PRODUCT_SUB_CODE), 1, 3), 0) AS PRODUCT_CATG
                                    FROM account_master m
                                    left outer join DISBURSEMENT_REQUEST d
                                    on m.account_no = d.account_no
                                    where ((trunc(d.cheque_Date) <= to_Date(?, 'DD MON YYYY')  and disburse_mode = 'CH' AND d.disb_status not in ('X', 'R','S'))
                                    or (trunc(d.autodebit_Date) <= to_Date(?, 'DD MON YYYY') and disburse_mode = 'AD' AND d.disb_status not in ('X', 'R','S') and (length(d.DEBIT_BANKACCT) > 0)))
                                    and m.account_status <> 2
                                    and m.branch_code = ?
                                    group by SUBSTR(UF_GET_PRODUCT_CATG(m.PRODUCT_CODE, m.PRODUCT_SUB_CODE), 1, 3)
                                    union
                                    SELECT count(*) AS bil,
                                        count(distinct M.Nokpbaru) as bilpeminjam,
                                        sum(M.Jumlahpinjaman) AS amt,
                                        M.Produk_Category AS PRODUCT_CATG
                                    FROM Migration_New M
                                    group by M.Produk_Category
                                )
                                group by nvl(uf_decode_prodcatg(product_catg), 0)
                            ", [$currentDate, $currentDate, auth()->user()->branchCode()]);

            $pivotData = [
                'product_categories' => [],
                'bil_peminjam' => [],
                'bil_akaun' => [],
                'jumlah_pembiayaan' => [],
            ];

            foreach ($pembiayanData as $result) {
                $pivotData['product_categories'][] = $result->product_category;
                $pivotData['bil_peminjam'][] = $result->bil_peminjam;
                $pivotData['bil_akaun'][] = $result->bilakaun;
                $pivotData['jumlah_pembiayaan'][] = number_format($result->jumlah_pembiayaan, 2);
            }

            return $pivotData;
        });

        $this->pmgiLevels = [
            'PM1' => 'PMG-i (1)',
            'PM2' => 'PMG-i (2)',
            'PM3' => 'PMG-i (3)',
            'JT1' => 'JTT (1)',
            'JT2' => 'JTT (2)',
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
                'a.bil_lawat_pts'
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
                DB::raw("TO_CHAR(b.session_date_start, 'YYYY-MM') as session_start_month"),
                DB::raw("TO_CHAR(b.session_date_end, 'YYYY-MM') as session_end_month"),
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
