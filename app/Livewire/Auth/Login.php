<?php

namespace App\Livewire\Auth;

use App\Models\ExcludeUserLogin;
use App\Models\SettUalPage;
use App\Models\SettUalRoleHasPage;
use App\Models\User;
use App\Services\General\LoginService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use WireUi\Traits\Actions;

class Login extends Component
{
    use Actions;

    /** @var string */
    public $userId = '';

    /** @var string */
    public $password = '';

    /** @var bool */
    public $remember = false;

    public $tnc = false;

    public $tnc2 = false;

    public $tncModal = false;

    public $tnc2Modal = false;

    public $disableButton = false;

    // User checking states
    public $excludeUser = null;
    public $canProceedToPassword = false;
    public $userMessage = null;

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

    public function mount()
    {
        // check flash error from middleware
        if (session()->has('flash_error')) {
            $this->dialog()->error(
                $title = 'Perhatian.',
                $description = session('flash_error')
            );
        }

        if (session()->has('flash_success')) {
            $this->dialog()->success(
                $title = 'Berjaya!',
                $description = session('flash_success')
            );
        }
    }

    public function updatedUserId()
    {
        $this->resetUserValidation();
        
        if (empty($this->userId)) {
            return;
        }

        $this->checkUserId();
    }

    private function resetUserValidation()
    {
        $this->excludeUser = null;
        $this->userMessage = '';
        $this->canProceedToPassword = false;
        $this->password = ''; // Clear password when user ID changes
    }

    // Approach 1
    private function checkUserId()
    {
        $excludeUserLogin = ExcludeUserLogin::where('userid', strtoupper($this->userId))
            ->first();

        if ($excludeUserLogin) {
            $this->addError('userId', trans('auth.notFound'));
            $this->disableButton = true;
            return;
        }

        $this->excludeUser = $excludeUserLogin !== null;
        $this->canProceedToPassword = !$this->excludeUser;
    }

    public function updatedTnc($value)
    {
        if ($value == true) {
            $this->tncModal = true;
        }
    }

    public function updatedTnc2($value)
    {
        if ($value == true) {
            $this->tnc2Modal = true;
        }
    }

    public function openTncCard()
    {
        $this->tncModal = !$this->tncModal;
    }

    public function openTnc2Card()
    {
        $this->tnc2Modal = !$this->tnc2Modal;
    }

    public function authenticate()
    {
        $this->validate();

        $upperUserId = strtoupper($this->userId);

        $user = User::where('USERID', $upperUserId)
                        ->where('USERSTATUS', 1)
                        ->first();

        if (!$user) {
            $this->addError('userId', trans('auth.notFound'));
            return;
        }

        // Check if the environment is not production
        if (app()->environment('production')) {
            if ($user->encryptflag != 2) {
                $savedpassword = LoginService::decrypting($user->userpassword);
            } else {
                $savedpassword = LoginService::of_decryptnew($user->userpassword);
            }

            if ($savedpassword !== $this->password) {
                $this->addError('password', trans('auth.failed'));
                return;
            }
        }

        Auth::login($user);

        $loggedUser = Auth::user();

        // Fetch access pages and roles
        $accessPages = SettUalPage::select('key')
            ->whereIn('id', SettUalRoleHasPage::whereIn('role_id', $loggedUser->roles()->pluck('role_id'))
                            ->pluck('page_id'))
            ->pluck('key')
            ->toArray();

        $userRoles = $loggedUser->roles()->pluck('role_id')->toArray();

        // Store access pages and roles in session
        Session::put('user_access_pages', $accessPages);
        Session::put('user_roles', $userRoles);

        return redirect()->intended(route('home'));
    }

    public function render()
    {
        return view('livewire.auth.login', [
            'isProduction' => app()->environment('production')
        ])->extends('layouts.auth');
    }
}
