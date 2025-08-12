<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalParm extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table="pmgi_sys_global_parm";
    protected $guarded = [];

    public $timestamps = false;
}
