<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExcludeUserLogin extends Model
{
    protected $table = 'pmgi_excl_user_login';
    public $timestamps = false;

    protected $fillable = [
        'userid',
        'username'
    ];
}
