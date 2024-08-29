<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SummMthOfficer extends Model
{
    use HasFactory;

    protected $table="PMGI_NAZ_SUMM_MTH_OFFICER";
    protected $guarded = [];
    public $timestamps = false;

    // Indicate that the model doesn't have a primary key
    protected $primaryKey = null;
    public $incrementing = false;
}
