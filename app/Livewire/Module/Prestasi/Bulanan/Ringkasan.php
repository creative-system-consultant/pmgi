<?php

namespace App\Livewire\Module\Prestasi\Bulanan;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Ringkasan extends Component
{
    public function render()
    {
        $officerData = DB::table('PMGI_SUMM_MTH_OFFICER as a')
            ->join('BANK_OFFICERS as b', 'a.officer_id', '=', 'b.officer_id')
            ->select(
                'a.report_date',
                'a.officer_name',
                'b.officer_position',
                'a.rm_dapat_kutip_pts',
                'a.bil_dapat_kutip_pts',
                'a.bil_lawat_pts',
                'a.bil_kawal_npf_pts',
                'a.bil_pulih_npf_pts',
                'a.pmgi_capai_flag'
            )
            ->where('a.officer_id', auth()->user()->userid)
            ->orderBy('a.report_date', 'desc')
            ->take(2)
            ->get();

        // Format the report_date field
        $officerData->transform(function ($item) {
            $item->report_date = Carbon::parse($item->report_date)->format('F Y');
            return $item;
        });

        // Group by officer_id
        $groupedData = collect($officerData)->groupBy('officer_id');

        return view('livewire.module.prestasi.bulanan.ringkasan', [
            'groupedData' => $groupedData,
        ]);
    }
}
