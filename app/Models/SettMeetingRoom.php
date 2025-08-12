<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettMeetingRoom extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table = "pmgi_sett_meeting_room";
    protected $guarded = [];
}
