<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionInfo extends Model
{
    use HasFactory;

    protected $table = "PMGI_SESSION_INFO";
    protected $guarded = [];
    public $timestamps = false;

    public function setting()
    {
        return $this->belongsTo(SettPymPmc::class, 'SESSION_ID', 'SESSION_ID');
    }

    public function pydInfo()
    {
        return $this->hasOne(SessionPydInfo::class, 'SESSION_ID', 'SESSION_ID');
    }
}
