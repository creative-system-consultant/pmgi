<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrdOfficer extends Model
{
    use HasFactory;

    protected $table="PMGI_HRD_OFFICER";
    protected $guarded = [];

    public $timestamps = false;
}
