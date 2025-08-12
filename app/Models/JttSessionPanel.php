<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JttSessionPanel extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table="pmgi_jtt_session_panel";
    protected $guarded = [];
    public $timestamps = false;
}
