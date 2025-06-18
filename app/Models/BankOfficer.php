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

    protected $primaryKey = 'OFFICER_ID';
    public $incrementing = false;
    protected $keyType = 'string';

    public function user()
    {
        return $this->belongsTo(User::class, 'OFFICER_ID', 'USERID');
    }

    public function branch()
    {
        return $this->hasOne(Branch::class, 'BRANCH_CODE', 'BRANCH_CODE');
    }

    public function hrData()
    {
        return $this->hasOne(HrdOfficer::class, 'NO_KP', 'NOKP');
    }
}
