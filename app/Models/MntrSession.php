<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MntrSession extends Model
{
    use HasFactory;

    protected $table="PMGI_NAZ_MNTR_SESSION";
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
        return $this->belongsTo(User::class, 'officer_id', 'userid');
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
        return $this->belongsTo(SettPymPmc::class, 'report_date', 'report_date')
                    ->where('pyd_id', $this->officer_id);
    }
}
