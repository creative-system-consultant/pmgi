<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JttSessionInfo extends Model
{
    use HasFactory;

    protected $table="PMGI_JTT_SESSION_INFO";
    protected $guarded = [];
    public $timestamps = false;

    public function participantInfo()
    {
        return $this->hasMany(JttSessionParticipant::class, 'session_id', 'session_id');
    }

    public function venueInfo()
    {
        return $this->belongsTo(SettMeetingRoom::class, 'venue', 'id');
    }
}
