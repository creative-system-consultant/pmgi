<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JttSessionParticipant extends Model
{
    use HasFactory;

    protected $table="PMGI_JTT_SESSION_PARTICIPANT";
    protected $guarded = [];
    public $timestamps = false;

    public function sessionInfo()
    {
        return $this->belongsTo(JttSessionInfo::class, 'SESSION_ID', 'SESSION_ID');
    }

    public function mntrSession()
    {
        return $this->hasOne(MntrSession::class, 'REPORT_DATE', 'REPORT_DATE');
    }
}
