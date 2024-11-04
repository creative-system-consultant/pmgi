<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalParm extends Model
{
    use HasFactory;

    protected $table="PMGI_SYS_GLOBAL_PARM";
    protected $guarded = [];

    public $timestamps = false;
}
