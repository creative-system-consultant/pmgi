<?php

namespace App\Livewire\Auth;

use App\Models\SettUalPage;
use App\Models\SettUalRoleHasPage;
use App\Models\User;
use App\Services\General\LoginService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Login extends Component
{
    /** @var string */
    public $userId = '';

    /** @var string */
    public $password = '';

    /** @var bool */
    public $remember = false;

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

    public function authenticate()
    {
        $this->validate();

        $user = User::where('userid', strtoupper($this->userId))
                        ->where('userstatus', 1)
                        ->first();

        if (!$user) {
            $this->addError('userId', trans('auth.notFound'));
            return;
        }

        // Check if the environment is not production
        if (app()->environment('production')) {
            $savedpassword = LoginService::decrypting($user->userpassword);

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
