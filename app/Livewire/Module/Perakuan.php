<?php

namespace App\Livewire\Module;

use App\Models\BankOfficer;
use App\Models\SessionInfo;
use App\Models\SessionPmcInfo;
use App\Models\SessionPydInfo;
use App\Models\SessionPymInfo;
use App\Models\SettPymPmc;
use App\Models\User;
use App\Services\General\LoginService;
use Carbon\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Component;
use WireUi\Traits\Actions;

class Perakuan extends Component
{
    use Actions;

    public $sessionId;
    public $source;
    public $verifyModal = false;
    public $pydName;
    public $pydIcNo;
    public $pydStaffNo;
    public $from;
    public $to;
    public $setting;
    public $userId;
    public $password;

    protected function rules()
    {
        $rules = [
            'userId' => ['required'],
        ];

        if (app()->environment('production')) {
            $rules['password'] = ['required'];
        }

        return $rules;
    }

    protected function messages()
    {
        return [
            'userId.required' => 'ID Pengguna diperlukan.',
            'password.required' => 'Kata laluan diperlukan.',
        ];
    }

    public function mount()
    {
        // check flash error from middleware
        if (session()->has('flash_error')) {
            $this->dialog()->error(
                $title = 'Ralat!',
                $description = session('flash_error')
            );
        }

        $this->sessionId = str_replace('-', '/', request()->query('session_id'));
        $this->source = request()->query('source');

        if ($this->sessionId) {
            $pyd = SettPymPmc::whereSessionId($this->sessionId)->value('pyd_id');
            $bankOfficer = BankOfficer::whereOfficerId($pyd)->first();
            $sessionDate = Carbon::parse(SessionInfo::whereSessionId($this->sessionId)->value('session_date'));
            $this->pydName = $bankOfficer->officer_name;
            $this->pydIcNo = $bankOfficer->nokp;
            $this->pydStaffNo = $bankOfficer->staffno;
            $this->from = $sessionDate->copy()->addMonth()->format('m/Y');
            $this->to = $sessionDate->copy()->addMonth(2)->format('m/Y');
            $this->setting = SettPymPmc::whereSessionId($this->sessionId)->first();
        }
    }

    public function verify(): void
    {
        $this->verifyModal = true;
    }

    public function save() {
        $this->validate();

        if (strtoupper($this->userId) != auth()->user()->userid) {
            $this->addError('userId', 'User ID yang dimasukkan tidak sama dengan User yang login untuk sesi ini.');
            return;
        }

        $user = User::where('userid', strtoupper($this->userId))
                        ->where('userstatus', 1)
                        ->first();

        // Check if the environment is not production
        if (app()->environment('production')) {
            $savedpassword = LoginService::decrypting($user->userpassword);

            if ($savedpassword !== $this->password) {
                $this->addError('password', trans('auth.failed'));
                return;
            }
        }

        $sessionModelMap = [
            'pyd' => SessionPydInfo::class,
            'pym' => SessionPymInfo::class,
            'pmc' => SessionPmcInfo::class,
        ];

        if (isset($sessionModelMap[$this->source])) {
            $sessionModelMap[$this->source]::where('session_id', $this->sessionId)
                ->update(['date_signed' => now()]);
        }

        return $this->redirect('/loading-perakuan?session_id=' . $this->sessionId);
    }

    public function render()
    {
        return view('livewire.module.perakuan')->extends('layouts.main');
    }
}
