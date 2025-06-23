<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettOfficerInfoFile extends Model
{
    use HasFactory;

    protected $table="pmgi_sett_officer_info_file";
    protected $guarded = [];
    public $timestamps = false;
}
