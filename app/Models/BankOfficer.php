<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankOfficer extends Model
{
    use HasFactory;

    protected $table="PMGI_FMS_BANK_OFFICERS";
    protected $guarded = [];
    public $timestamps = false;

    protected $primaryKey = 'officer_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function user()
    {
        return $this->belongsTo(User::class, 'officer_id', 'userid');
    }

    public function branch()
    {
        return $this->hasOne(Branch::class, 'branch_code', 'branch_code');
    }

    public function hrData()
    {
        return $this->hasOne(HrdOfficer::class, 'no_kp', 'nokp');
    }
}
