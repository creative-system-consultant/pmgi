<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionJttPydInfo extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table="pmgi_session_jtt_pyd_info";
    protected $guarded = [];
    public $timestamps = false;
}
