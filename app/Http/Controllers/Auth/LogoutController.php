<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserAccess;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        $user = Auth::user();
        
        // Mark current session as logged out for PYD and PYM users
        if ($user) {
            $userRoles = $user->roles()->pluck('role_id')->toArray();
            
            if (array_intersect($userRoles, [4,5])) {
                UserAccess::where('user_id', $user->USERID)
                    ->where('session_id', session()->getId())
                    ->whereNull('logout_dt')
                    ->update([
                        'logout_dt' => now(),
                    ]);
            }
        }

        // Clear session data related to access control
        Session::forget('user_access_pages');
        Session::forget('user_roles');


        Auth::logout();

        // Invalidate the session and regenerate token for security
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect(route('home'));
    }
}
