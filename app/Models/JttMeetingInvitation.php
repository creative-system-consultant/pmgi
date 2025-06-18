<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JttMeetingInvitation extends Model
{
    use HasFactory;

    protected $table="PMGI_JTT_MEETING_INVITATIONS";
    protected $guarded = [];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function officer()
    {
        return $this->belongsTo(BankOfficer::class, 'OFFICER_ID', 'OFFICER_ID');
    }
}
