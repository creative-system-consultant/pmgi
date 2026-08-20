<?php

namespace App\Livewire\Module\Prestasi\Bulanan;

use App\Models\RefEvalPctg;
use App\Services\Prestasi\PrestasiBulananKeseluruhanService;
use Livewire\Component;

class Keseluruhan extends Component
{
    public $role;
    public $state;
    public $branch;
    public $pydId;
    public $date;
    public $percentage;

    public function render()
    {
        $service = new PrestasiBulananKeseluruhanService();

        if ($this->role != 'pyd') {
            $rows = $service->forAdmin($this->date, $this->state, $this->branch, $this->pydId);
        } else {
            $rows = $service->forOfficer($this->date, auth()->user()->USERID);
        }

        $this->percentage = RefEvalPctg::where('state_code', '01') // once da approve utk by negeri, tukar ni.. skrg pkai 01 sbb smua negeri sama value
                ->whereDate('effective_date', '<=', $this->date)
                ->orderBy('evaluation_id', 'ASC')
                ->get();

        return view('livewire.module.prestasi.bulanan.keseluruhan', [
            'rows' => $rows,
        ]);
    }
}
