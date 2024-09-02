<?php

namespace App\Http\Middleware;

use App\Models\SessionInfo;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        $sessionId = str_replace('-', '/', $request->session_id);

        // Check if there's an active session for today
        $session = SessionInfo::whereSessionId($sessionId)
                                ->whereDate('session_date', now())
                                ->where('status', 0) // Assuming status 0 means the session is active
                                ->whereHas('setting', function ($query) use ($user) {
                                    $query->where(function ($q) use ($user) {
                                        $q->where('pyd_id', $user->userid)
                                            ->orWhere('pym_id', $user->userid)
                                            ->orWhere('pmc_id', $user->userid);
                                    });
                                })
                                ->first();

        if (!$session) {
            // If no active session, redirect to a page (e.g., home or dashboard) with an error message
            return redirect()->route('home')->with('flash_error', 'Tiada sesi aktif untuk hari ini.');
        }

        // If there is an active session, allow the request to proceed
        return $next($request);
    }
}
