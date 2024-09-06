<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettJtt extends Model
{
    use HasFactory;

    protected $table="PMGI_SETT_JTT";
    protected $guarded = [];
    public $timestamps = false;

    public function bankOfficer()
    {
        return $this->hasOne(BankOfficer::class, 'officer_id', 'officer_id');
    }

    protected function getBankOfficer()
    {
        return $this->bankOfficer()->first();
    }

    public function name()
    {
        return optional($this->getBankOfficer())->officer_name;
    }

    public function position()
    {
        return optional($this->getBankOfficer())->officer_position;
    }

    public function email()
    {
        return optional($this->getBankOfficer())->email;
    }
}
