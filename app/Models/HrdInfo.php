<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrdInfo extends Model
{
    use HasFactory;

    protected $table="PMGI_HRD_INFO";
    protected $guarded = [];

    public $timestamps = false;
}
