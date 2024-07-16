<?php

namespace App\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Home extends Component
{
    public $pmgiLevels;
    public $pmgiResults;

    public function mount()
    {
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

        return view('livewire.home', [
            'officerDatas' => $officerData
        ])->extends('layouts.main');
    }
}
