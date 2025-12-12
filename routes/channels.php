<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('pmgi.session.{sessionId}', function ($user, $sessionId) {
    $session = \App\Models\SettPymPmc::where('session_id', str_replace('-', '/', $sessionId))->first();
    if (! $session) return false;
    return in_array($user->USERID, [$session->pyd_id, $session->pym_id, $session->pmc_id]);
});