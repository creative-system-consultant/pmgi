<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccess extends Model
{
    protected $table = 'pmgi_user_access';
    protected $guarded = [];
    public $timestamps = false;
    protected $fillable = ['user_id', 'login_dt', 'logout_dt', 'session_id'];
}
