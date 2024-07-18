<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettOfficerInfoFile extends Model
{
    use HasFactory;

    protected $table="PMGI_SETT_OFFICER_INFO_FILE";
    protected $guarded = [];
    public $timestamps = false;
}
