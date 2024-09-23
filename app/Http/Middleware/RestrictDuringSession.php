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

class RestrictDuringSession
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
                                ->whereHas('setting', function ($query) use ($user) {
                                    $query->where(function ($q) use ($user) {
                                        $q->where('pyd_id', $user->userid)
                                        ->orWhere('pym_id', $user->userid)
                                        ->orWhere('pmc_id', $user->userid);
                                    });
                                })
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
                $pydUrl = route('pegawai-dinilai', ['session_id' => $encodedSessionId]);
                $pymUrl = route('pegawai-menilai', ['session_id' => $encodedSessionId]);
                $pmcUrl = route('pegawai-pemudah-cara', ['session_id' => $encodedSessionId]);

                $message = 'Sesi sedang berlangsung. Anda hanya boleh mengakses halaman ini sehingga sesi selesai.';

                $pydRecordExists = SessionPydInfo::where('session_id', $sessionId)->exists();
                $pymRecordExists = SessionPymInfo::where('session_id', $sessionId)->exists();
                $pmcRecordExists = SessionPmcInfo::where('session_id', $sessionId)->exists();

                // Check if the user is the PYD and redirect if necessary
                if ($user->userid === $settPymPmc->pyd_id) {
                    if ($pmgiLvl != 3) {
                        if ($pydRecordExists && $pymRecordExists) {
                            return redirect(route('perakuan', ['session_id' => $encodedSessionId, 'source' => 'pyd']))->with('flash_error', 'Sila tunggu sehingga sesi ini selesai.');
                        } elseif ($pydRecordExists && !$pymRecordExists) {
                            return redirect(route('loading-pmgi', ['session_id' => $encodedSessionId, 'source' => 'pyd']))->with('flash_error', 'Sila tunggu sehingga penilai selesai membuat penilaian.');
                        } else{
                            return redirect($pydUrl)->with('flash_error', $message);
                        }
                    } else {
                        if ($pydRecordExists && $pymRecordExists && $pmcRecordExists) {
                            return redirect(route('perakuan', ['session_id' => $encodedSessionId, 'source' => 'pyd']))->with('flash_error', 'Sila tunggu sehingga sesi ini selesai.');
                        } elseif ($pydRecordExists && (!$pymRecordExists || !$pmcRecordExists)) {
                            return redirect(route('loading-pmgi', ['session_id' => $encodedSessionId, 'source' => 'pyd']))->with('flash_error', 'Sila tunggu sehingga penilai selesai membuat penilaian.');
                        } else {
                            return redirect($pydUrl)->with('flash_error', $message);
                        }
                    }
                }

                // Check if the user is the PYM and redirect if necessary
                if ($user->userid === $settPymPmc->pym_id) {
                    if ($pmgiLvl != 3) {
                        if ($pydRecordExists && $pymRecordExists) {
                            return redirect(route('perakuan', ['session_id' => $encodedSessionId, 'source' => 'pym']))->with('flash_error', 'Sila tunggu sehingga sesi ini selesai.');
                        } elseif (!$pydRecordExists && $pymRecordExists) {
                            return redirect(route('loading-pmgi', ['session_id' => $encodedSessionId, 'source' => 'pym']))->with('flash_error', 'Sila tunggu sehingga penilai selesai membuat penilaian.');
                        } else{
                            return redirect($pymUrl)->with('flash_error', $message);
                        }
                    } else {
                        if ($pydRecordExists && $pymRecordExists && $pmcRecordExists) {
                            return redirect(route('perakuan', ['session_id' => $encodedSessionId, 'source' => 'pym']))->with('flash_error', 'Sila tunggu sehingga sesi ini selesai.');
                        } elseif ($pymRecordExists && (!$pydRecordExists || !$pmcRecordExists)) {
                            return redirect(route('loading-pmgi', ['session_id' => $encodedSessionId, 'source' => 'pym']))->with('flash_error', 'Sila tunggu sehingga penilai selesai membuat penilaian.');
                        } else {
                            return redirect($pymUrl)->with('flash_error', $message);
                        }
                    }
                }

                // Check if the user is the PMC and redirect if necessary
                if ($settPymPmc->pmgi_level === 'PM3' && $user->userid === $settPymPmc->pmc_id) {
                    if ($pydRecordExists && $pymRecordExists && $pmcRecordExists) {
                        return redirect(route('perakuan', ['session_id' => $encodedSessionId, 'source' => 'pmc']))->with('flash_error', 'Sila tunggu sehingga sesi ini selesai.');
                    } elseif ((!$pydRecordExists || !$pymRecordExists) && $pmcRecordExists) {
                        return redirect(route('loading-pmgi', ['session_id' => $encodedSessionId, 'source' => 'pmc']))->with('flash_error', 'Sila tunggu sehingga penilai selesai membuat penilaian.');
                    } else {
                        return redirect($pmcUrl)->with('flash_error', $message);
                    }
                }
            }
        }

        return $next($request);
    }
}
