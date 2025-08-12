<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JttSessionInfo extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table="pmgi_jtt_session_info";
    protected $guarded = [];
    public $timestamps = false;

    public function panelInfo()
    {
        return $this->hasMany(JttSessionPanel::class, 'session_id', 'session_id');
    }

    public function venueInfo()
    {
        return $this->belongsTo(SettMeetingRoom::class, 'venue', 'id');
    }
}
