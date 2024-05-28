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

    protected $primaryKey = 'branch_code';
    public $incrementing = false;
    protected $keyType = 'string';

    public function bankOfficer()
    {
        return $this->hasMany(BankOfficer::class, 'branch_code', 'branch_code');
    }

    public function bnmState()
    {
        return $this->hasOne(BnmStatecode::class, 'code', 'state_code');
    }
}
