<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table="BRANCHES";
    protected $guarded = [];
    public $timestamps = false;

    protected $primaryKey = 'BRANCH_CODE';
    public $incrementing = false;
    protected $keyType = 'string';

    public function bankOfficer()
    {
        return $this->hasMany(BankOfficer::class, 'BRANCH_CODE', 'BRANCH_CODE');
    }

    public function bnmState()
    {
        return $this->hasOne(BnmStatecode::class, 'CODE', 'STATE_CODE');
    }
}
