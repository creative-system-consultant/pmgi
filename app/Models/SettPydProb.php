<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettPydProb extends Model
{
    use HasFactory;

    protected $table="pmgi_sett_pyd_prob";
    protected $guarded = [];
    public $timestamps = false;
}
