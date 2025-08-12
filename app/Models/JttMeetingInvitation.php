<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JttMeetingInvitation extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table="pmgi_jtt_meeting_invitations";
    protected $guarded = [];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function officer()
    {
        return $this->belongsTo(BankOfficer::class, 'officer_id', 'officer_id');
    }
}
