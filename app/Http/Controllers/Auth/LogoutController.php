<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        // Clear session data related to access control
        Session::forget('user_access_pages');
        Session::forget('user_roles');


        Auth::logout();

        return redirect(route('home'));
    }
}
