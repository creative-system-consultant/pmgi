<?php

namespace App\Livewire\Module\Prestasi\Bulanan;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Keseluruhan extends Component
{
    public function render()
    {
        $sql = DB::table('PMGI_SUMM_MTH_OFFICER as a')
            ->where('a.officer_id', auth()->user()->userid)
            ->orderBy('a.report_date', 'desc')
            ->take(1);

        $titleData = $sql->first();
        $officerData = $sql->get();

        // Format the report_date field
        $officerData->transform(function ($item) {
            $item->report_date = Carbon::parse($item->report_date)->format('F Y');
            return $item;
        });

        return view('livewire.module.prestasi.bulanan.keseluruhan', [
            'titleData' => $titleData,
            'officerData' => $officerData,
        ]);
    }
}
