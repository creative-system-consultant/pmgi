<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettUalRoleHasPage extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table = "pmgi_sett_ual_role_has_page";
    protected $guarded = [];
    public $timestamps = false;
}
