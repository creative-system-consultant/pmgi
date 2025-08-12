<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcludeUserLogin extends Model
{
    use HasFactory;
    
    protected $connection = 'sqlsrv';
    protected $table = 'pmgi_excl_user_login';
    public $timestamps = false;

    protected $fillable = [
        'userid',
        'username'
    ];
}
