<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionPydInfo extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table="pmgi_session_pyd_info";
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

    public function problemTable()
    {
        return $this->belongsTo(SettPydProb::class, 'problem', 'id');
    }
}
