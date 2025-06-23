<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrdOfficer extends Model
{
    use HasFactory;

    protected $table="pmgi_hrd_officer";
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

    public function bankOfficer()
    {
        return $this->hasOne(BankOfficer::class, 'staffno', 'no_pekerja');
    }
}
