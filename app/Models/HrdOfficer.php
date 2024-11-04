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

    protected $casts = [
        'tarikh_kuatkuasa' => 'datetime',
        'resign_date' => 'datetime',
        'tarikh_cuti_dari' => 'datetime',
        'tarikh_cuti_hingga' => 'datetime',
        'tarikh_lantikan' => 'datetime',
        'date_disiplin' => 'datetime',
    ];
}
