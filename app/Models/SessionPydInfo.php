<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionPydInfo extends Model
{
    use HasFactory;

    protected $table="PMGI_SESSION_PYD_INFO";
    protected $guarded = [];
    public $timestamps = false;
    protected $casts = [
        'date_signed' => 'datetime',
    ];

    public function setting()
    {
        return $this->belongsTo(SettPymPmc::class, 'SESSION_ID', 'SESSION_ID');
    }

    public function info()
    {
        return $this->belongsTo(SessionInfo::class, 'SESSION_ID', 'SESSION_ID');
    }

    public function problemTable()
    {
        return $this->belongsTo(SettPydProb::class, 'PROBLEM', 'ID');
    }
}
