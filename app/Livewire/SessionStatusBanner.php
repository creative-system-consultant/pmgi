<?php

namespace App\Livewire;

use App\Models\SessionInfo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SessionStatusBanner extends Component
{
    public $hasActiveSession = false;
    public $sessionInfo = null;

    public function mount()
    {
        $this->checkSessionStatus();
    }

    public function checkSessionStatus()
    {
        $user = Auth::user();

        $this->sessionInfo = SessionInfo::whereDate('session_date', now())
            ->where('status', 0)
            ->whereHas('setting', function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('pyd_id', $user->userid)
                        ->orWhere('pym_id', $user->userid)
                        ->orWhere('pmc_id', $user->userid);
                });
            })
            ->first();

        $this->hasActiveSession = !is_null($this->sessionInfo);
    }

    public function render()
    {
        return view('livewire.session-status-banner');
    }
}