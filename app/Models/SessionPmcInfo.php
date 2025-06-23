<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionPmcInfo extends Model
{
    use HasFactory;

    protected $table="pmgi_session_pmc_info";
    protected $guarded = [];
    public $timestamps = false;
    protected $casts = [
        'date_signed' => 'datetime',
    ];

    public function setting()
    {
        return $this->belongsTo(SettPymPmc::class, 'session_id', 'session_id');
    }

    public function info()
    {
        return $this->belongsTo(SessionInfo::class, 'session_id', 'session_id');
    }
}
