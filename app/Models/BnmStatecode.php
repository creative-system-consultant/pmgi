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

    protected $primaryKey = 'CODE';
    public $incrementing = false;
    protected $keyType = 'string';

    public function branches()
    {
        return $this->hasMany(Branch::class, 'STATE_CODE', 'CODE');
    }

    public function committee()
    {
        return $this->hasOne(SettStateCommittee::class, 'STATECODE', 'CODE');
    }
}
