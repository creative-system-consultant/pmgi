<?php

namespace App\Livewire\Home;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Pyd extends Component
{
    public $pmgiLevels;
    public $pmgiResults;
    public $penjadualanSemula;
    public $mia;
    public $pembiayaan;

    public function mount()
    {
        $this->penjadualanSemula = DB::table('RESCHEDULE_INFO2 as rs')
                        ->join('account_master as m', 'rs.accountno', '=', 'm.account_no')
                        ->where('activestatus', '>=', 0)
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

        $this->mia = DB::table('mia_appl_list as ma')
                        ->join('account_master as m', 'ma.custid', '=', 'm.cust_id')
                        ->selectRaw('
                            count(*) as jumlah,
                            sum(case when ma.status = 1 then 1 else 0 end) as lulus,
                            sum(case when ma.status = 0 then 1 else 0 end) as proses,
                            sum(case when ma.status = -1 then 1 else 0 end) as tolak,
                            sum(case when ma.status = -2 then 1 else 0 end) as dikembalikan
                        ')
                        ->first();

        $currentDate = now()->format('d M Y');

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
                        ", [$currentDate, $currentDate]);

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

        $this->pembiayaan = $pivotData;

        $this->pmgiLevels = [
            'PM1' => 'PMG-i (1)',
            'PM2' => 'PMG-i (2)',
            'PM3' => 'PMG-i (3)',
            'JT1' => 'JTT (1)',
            'JT2' => 'JTT (2)',
        ];

        $this->pmgiResults = [
            'PM3' => [
                'TSK' => ['text' => 'TIDAK SYOR<br>KELUAR', 'class' => 'bg-yellow-100 text-yellow-800'],
                'TGH' => ['text' => 'TANGGUH', 'class' => 'bg-yellow-100 text-yellow-800'],
                'SYA' => ['text' => 'KELUAR<br>BERSYARAT', 'class' => 'bg-yellow-100 text-yellow-800'],
                'TSY' => ['text' => 'KELUAR TANPA<br>SYARAT', 'class' => 'bg-green-100 text-green-800'],
            ],
            'JT1' => [
                'GDI' => ['text' => 'DIBERI TEMPOH<br>TAMBAHAN', 'class' => 'bg-yellow-100 text-yellow-800'],
                'DIQ' => ['text' => 'DOMESTIC<br>INQUIRY', 'class' => 'bg-red-100 text-red-800'],
            ],
            'JT2' => [
                'KSR' => ['text' => 'KELUAR<br>SENARAI', 'class' => 'bg-green-100 text-green-800'],
                'DIQ' => ['text' => 'DOMESTIC<br>INQUIRY', 'class' => 'bg-red-100 text-red-800'],
            ],
            'HRD' => [
                'TBI' => ['text' => 'TIDAK<br>DIBERHENTIKAN', 'class' => 'bg-green-100 text-green-800'],
                'DBI' => ['text' => 'DIBERHENTIKAN', 'class' => 'bg-red-100 text-red-800'],
            ],
        ];
    }

    public function render()
    {
        $officerData = DB::table('PMGI_SUMM_MTH_OFFICER as a')
            ->leftJoin('PMGI_MNTR_SESSION as b', function ($join) {
                $join->on('a.officer_id', '=', 'b.officer_id')
                    ->whereRaw("TO_CHAR(a.report_date, 'MM-YYYY') = TO_CHAR(b.session_date_start, 'MM-YYYY')")
                    ->orWhereRaw("TO_CHAR(a.report_date, 'MM-YYYY') = TO_CHAR(b.session_date_end, 'MM-YYYY')");
            })
            ->where('a.officer_id', auth()->user()->userid)
            ->select(
                'a.report_date', 'a.officer_id', 'b.pmgi_result', 'b.pmgi_level',
                'a.bil_selia', 'a.bil_dapat_kutip',
                'a.rm_patut_kutip', 'a.rm_dapat_kutip', 'a.rm_dapat_kutip_pts',
                'a.bil_lawat', 'a.bil_lawat_pts'
            )
            ->orderBy('a.report_date', 'asc')  //need to change to desc later.. now use asc since only earlier data has result
            ->take(8)
            ->get();

        // Format the report_date field
        $officerData->transform(function ($item) {
            $item->report_date = Carbon::parse($item->report_date)->format('M-y');
            return $item;
        });

        return view('livewire.home.pyd', [
            'officerDatas' => $officerData
        ]);
    }
}
