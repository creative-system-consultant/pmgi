<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BnmStatecode extends Model
{
    use HasFactory;

    protected $table="BNM_STATECODES";
    protected $guarded = [];
    public $timestamps = false;

    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';

    public function branches()
    {
        return $this->hasMany(Branch::class, 'state_code', 'code');
    }

    public function committee()
    {
        return $this->hasOne(SettStateCommittee::class, 'statecode', 'code');
    }
}
