<?php

namespace App\Http\Middleware;

use App\Models\SessionInfo;
use App\Models\SessionPmcInfo;
use App\Models\SessionPydInfo;
use App\Models\SessionPymInfo;
use App\Models\SettPymPmc;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RestrictLoadingAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Check if there's an active session for today
        $session = SessionInfo::whereDate('session_date', now())
                                ->where('status', 0) // Assuming status 0 means the session is active
                                ->first();

        if ($session) {
            $sessionId = $session->session_id;
            $pmgiLvl = substr($session->session_id, 3, 1);

            // Replace slashes with a hyphen in the session_id for the URL
            $encodedSessionId = str_replace('/', '-', $session->session_id);

            // Fetch the session setting details
            $settPymPmc = SettPymPmc::where('session_id', $session->session_id)->first();

            if ($settPymPmc) {
                // Define the possible routes for the users with encoded session_id
                $loadingRoute = route('loading', ['session_id' => $encodedSessionId]);
                $loadingMessage = 'Sila tunggu sehingga semua peserta sesi membuat penilaian.';

                $perakuanRoute = route('perakuan', ['session_id' => $encodedSessionId]);
                $perakuanMessage = 'Tidak dibernarkan ke halaman sebelum ini. Sila buat perakuan.';

                $pydRecordExists = SessionPydInfo::where('session_id', $sessionId)->exists();
                $pymRecordExists = SessionPymInfo::where('session_id', $sessionId)->exists();
                $pmcRecordExists = SessionPmcInfo::where('session_id', $sessionId)->exists();

                // Check if the user is the PYD and redirect if necessary
                if ($user->userid === $settPymPmc->pyd_id) {
                    if ($pmgiLvl != 3) {
                        if ($pydRecordExists && !$pymRecordExists) {
                            return redirect($loadingRoute)->with('flash_error', $loadingMessage);
                        } elseif ($pydRecordExists && $pymRecordExists) {
                            return redirect($perakuanRoute)->with('flash_error', $perakuanMessage);
                        } elseif (!$pydRecordExists) {
                            return $next($request);
                        }
                    } else {
                        if ($pydRecordExists && (!$pymRecordExists || !$pmcRecordExists)) {
                            return redirect($loadingRoute)->with('flash_error', $loadingMessage);
                        } elseif ($pydRecordExists && $pymRecordExists && $pmcRecordExists) {
                            return redirect($perakuanRoute)->with('flash_error', $perakuanMessage);
                        } elseif (!$pydRecordExists) {
                            return $next($request);
                        }
                    }
                }

                // Check if the user is the PYM and redirect if necessary
                if ($user->userid === $settPymPmc->pym_id) {
                    if ($pmgiLvl != 3) {
                        if (!$pydRecordExists && $pymRecordExists) {
                            return redirect($loadingRoute)->with('flash_error', $loadingMessage);
                        } elseif ($pydRecordExists && $pymRecordExists) {
                            return redirect($perakuanRoute)->with('flash_error', $perakuanMessage);
                        } elseif (!$pymRecordExists) {
                            return $next($request);
                        }
                    } else {
                        if ($pymRecordExists && (!$pydRecordExists || !$pmcRecordExists)) {
                            return redirect($loadingRoute)->with('flash_error', $loadingMessage);
                        } elseif ($pymRecordExists && $pydRecordExists && $pmcRecordExists) {
                            return redirect($perakuanRoute)->with('flash_error', $perakuanMessage);
                        } elseif (!$pymRecordExists) {
                            return $next($request);
                        }
                    }
                }

                // Check if the user is the PMC and redirect if necessary
                if ($settPymPmc->pmgi_level === 'PM3' && $user->userid === $settPymPmc->pmc_id) {
                    if ((!$pydRecordExists || !$pymRecordExists) && $pmcRecordExists) {
                        return redirect($loadingRoute)->with('flash_error', $loadingMessage);
                    } elseif ($pydRecordExists && $pymRecordExists && $pmcRecordExists) {
                        return redirect($perakuanRoute)->with('flash_error', $perakuanMessage);
                    } elseif (!$pmcRecordExists) {
                        return $next($request);
                    }
                }
            }
        }

        return $next($request);
    }
}
