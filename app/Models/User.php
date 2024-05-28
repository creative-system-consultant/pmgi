<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table="FMS_USERS";
    protected $guarded = [];
    public $timestamps = false;

    protected $primaryKey = 'userid';
    public $incrementing = false;
    protected $keyType = 'string';

    public function bankOfficer()
    {
        return $this->hasOne(BankOfficer::class, 'officer_id', 'userid');
    }

    protected function getBankOfficer()
    {
        return $this->bankOfficer()->first();
    }

    public function staffNo()
    {
        return optional($this->getBankOfficer())->staffno;
    }

    public function branchCode()
    {
        return optional($this->getBankOfficer())->branch_code;
    }

    public function branchName()
    {
        return optional(optional($this->getBankOfficer())->branch)->branch_name;
    }

    public function branchType()
    {
        return optional(optional($this->getBankOfficer())->branch)->branch_type;
    }

    public function stateCode()
    {
        return optional(optional($this->getBankOfficer())->branch)->state_code;
    }

    public function stateName()
    {
        return optional(optional(optional($this->getBankOfficer())->branch)->bnmState)->description;
    }

    public function position()
    {
        return optional($this->getBankOfficer())->officer_position;
    }
}
