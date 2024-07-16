<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Services\General\LoginService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        $user = User::where('userid', strtoupper($this->userId))->first();

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

        return redirect()->intended(route('home'));
    }

    public function render()
    {
        return view('livewire.auth.login', [
            'isProduction' => app()->environment('production')
        ])->extends('layouts.auth');
    }
}
