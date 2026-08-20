<?php

namespace App\Livewire\Module\Prestasi\Bulanan;

use App\Services\Prestasi\PrestasiBulananRingkasanService;
use Livewire\Component;

class Ringkasan extends Component
{
    public $role;
    public $state;
    public $branch;
    public $pydId;
    public $date;

    public function render()
    {
        $service = new PrestasiBulananRingkasanService();

        if ($this->role != 'pyd') {
            $data = $service->forAdmin($this->date, $this->state, $this->branch, $this->pydId);
        } else {
            $data = $service->forOfficer($this->date, auth()->user()->USERID);
        }

        return view('livewire.module.prestasi.bulanan.ringkasan', [
            'rows' => $data['rows'],
            'months' => $data['months'],
        ]);
    }
}
