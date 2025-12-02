<?php

namespace App\Http\Middleware;

use App\Models\UserAccess;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SingleSessionLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply to authenticated users
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $user_roles = $user->roles()->pluck('role_id')->toArray();
        
        // Only apply to PYD (role_id = 4) and PYM (role_id = 5)
        // if (!in_array($user_roles, [4, 5])) {
        if (!array_intersect($user_roles, [4, 5])) {
            return $next($request);
        }

        $session_id = session()->getId();

        // Check if current session has been logged out
        $existing_session = UserAccess::where('session_id', $session_id)
            ->where('user_id', $user->USERID)
            ->latest('login_dt')
            ->first();

        if ($existing_session && $existing_session->logout_dt) {
            // Session::flush();
            Session::forget($session_id);
            Auth::logout();
            
            return redirect()->route('login')->with('error', 'Your session has been terminated because you logged in from another device.');
        }

        return $next($request);
    }
}
