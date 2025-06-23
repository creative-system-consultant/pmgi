<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettUalUserHasRole extends Model
{
    use HasFactory;

    protected $table = "pmgi_sett_ual_user_has_role";
    protected $guarded = [];
    public $timestamps = false;
}
