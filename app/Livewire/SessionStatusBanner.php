<?php

namespace App\Livewire;

use App\Models\SessionInfo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use WireUi\Traits\Actions;

class SessionStatusBanner extends Component
{
    use Actions;
    public $hasActiveSession = false;
    public $sessionInfo = null;

    public function mount()
    {
        if (!Auth::check()) {
            session()->flash('flash_error', 'Sila log masuk sebelum menggunakan sistem');
            return redirect()->route('login');
        }

        $this->checkSessionStatus();
    }

    public function checkSessionStatus()
    {
        $user = Auth::user()->USERID;
        $today = now()->toDateString();

        $this->hasActiveSession = SessionInfo::query()
            ->where('session_date', $today)
            ->whereNull('status')
            ->whereHas('setting', function ($query) use ($user) {
                $query->where('pyd_id', $user)
                    ->orWhere('pym_id', $user)
                    ->orWhere('pmc_id', $user);
            })
            ->exists();
    }

    public function render()
    {
        return view('livewire.session-status-banner');
    }
}