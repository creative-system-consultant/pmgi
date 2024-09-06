<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionJttPydInfo extends Model
{
    use HasFactory;

    protected $table="PMGI_SESSION_JTT_PYD_INFO";
    protected $guarded = [];
    public $timestamps = false;
}
