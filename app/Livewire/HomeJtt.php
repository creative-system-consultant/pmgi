<?php

namespace App\Livewire;

use App\Mail\JttMeetingInvitation as MailJttMeetingInvitation;
use App\Models\JttMeetingInvitation;
use App\Models\JttSessionInfo;
use App\Models\JttSessionParticipant;
use App\Models\MntrSession;
use App\Models\SettJtt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Illuminate\Support\Str;

class HomeJtt extends Component
{
    public $jttRoles = [];

    public function mount()
    {
        $jttOfficers = SettJtt::all();
        foreach ($jttOfficers as $officer) {
            $this->jttRoles[$officer->id] = '';
        }
    }

    public function startMeeting()
    {
        $selectedOfficers = array_filter($this->jttRoles, function($role) {
            return $role !== '';
        });

        $sessionId = $this->generateSessionId();

        // save meeting info
        $jttInfo = JttSessionInfo::create([
            'session_id' => $sessionId,
            'session_date' => now(),
            'created_by' => auth()->user()->userid,
        ]);

        foreach ($selectedOfficers as $officerId => $role) {
            $officer = SettJtt::find($officerId);

            // Generate a unique token for this invitation
            $token = Str::random(32);

            // Create a new meeting invitation record
            JttMeetingInvitation::create([
                'officer_id' => $officer->officer_id,
                'role' => $role,
                'token' => $token,
                'expires_at' => now()->addHour(),
            ]);

            // save participant
            JttSessionParticipant::create([
                'session_id' => $sessionId,
                'panel_id' => $officer->officer_id,
            ]);

            Mail::to($officer->email())->queue(new MailJttMeetingInvitation($token));
        }

        return redirect()->route('list-pyd-jtt', ['sessionId' => $sessionId])->with('flash_success', 'Jemputan telah dihantar pada emel panel yang terlibat.');
    }

    private function generateSessionId()
    {
        $datePart = now()->format('Ym');
        $formattedDatePart = substr($datePart, 2, 2) . substr($datePart, 4, 2);
        return 'JTT' . $formattedDatePart;
    }

    public function confirmAttendance($token)
    {
        $invitation = JttMeetingInvitation::where('token', $token)->first();

        if (!$invitation) {
            return redirect()->route('jtt.attendance', ['status' => 'notFound']);
        }

        if ($invitation->expires_at <= now()) {
            return redirect()->route('jtt.attendance', ['status' => 'expired']);
        }

        // Mark the invitation as confirmed
        $invitation->update(['confirmed_at' => now()]);

        return redirect()->route('jtt.attendance', ['status' => 'success']);
    }

    public function render()
    {
        $jttOfficer = SettJtt::all();

        $dataCount = MntrSession::whereIn('pmgi_level', ['JT1', 'JT2'])
                            ->where('session_date_start', '<=', now()->format('Y-m-d H:i:s'))
                            ->where('session_date_end', '>=', now()->format('Y-m-d H:i:s'))
                            ->count();

        return view('livewire.home-jtt', [
            'jttOfficers' => $jttOfficer,
            'dataCount' => $dataCount,
        ])->extends('layouts.main');
    }
}
