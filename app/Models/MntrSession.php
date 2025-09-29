<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MntrSession extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table="pmgi_mntr_session";
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null;

    public function getKeyName()
    {
        return null;
    }

    public static function findFirst($conditions)
    {
        return static::where($conditions)->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'officer_id', 'USERID');
    }

    public function branch()
    {
        return $this->hasOne(Branch::class, 'branch_code', 'branch_code');
    }

    public function state()
    {
        return $this->hasOne(BnmStatecode::class, 'code', 'state_code');
    }

    public function settPymPmc()
    {
        return $this->hasOne(SettPymPmc::class, 'report_date', 'report_date');
    }

    public function bankOfficer()
    {
        return $this->hasOne(BankOfficer::class, 'officer_id', 'officer_id');
    }
}
