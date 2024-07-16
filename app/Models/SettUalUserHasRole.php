<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettUalUserHasRole extends Model
{
    use HasFactory;

    protected $table = "PMGI_SETT_UAL_USER_HAS_ROLE";
    protected $guarded = [];
    public $timestamps = false;
}
