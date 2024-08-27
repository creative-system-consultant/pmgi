<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionPymInfo extends Model
{
    use HasFactory;

    protected $table="PMGI_SESSION_PYM_INFO";
    protected $guarded = [];
    public $timestamps = false;

    public function setting()
    {
        return $this->belongsTo(SettPymPmc::class, 'session_id', 'session_id');
    }

    public function info()
    {
        return $this->belongsTo(SessionInfo::class, 'session_id', 'session_id');
    }
}
