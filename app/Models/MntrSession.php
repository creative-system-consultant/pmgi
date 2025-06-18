<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MntrSession extends Model
{
    use HasFactory;

    protected $table="PMGI_MNTR_SESSION";
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
        return $this->belongsTo(User::class, 'OFFICER_ID', 'USERID');
    }

    public function branch()
    {
        return $this->hasOne(Branch::class, 'BRANCH_CODE', 'BRANCH_CODE');
    }

    public function state()
    {
        return $this->hasOne(BnmStatecode::class, 'CODE', 'STATE_CODE');
    }

    public function settPymPmc()
    {
        return $this->hasOne(SettPymPmc::class, 'REPORT_DATE', 'REPORT_DATE');
    }
}
