<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SummMthOfficer extends Model
{
    use HasFactory;

    protected $table="PMGI_SUMM_MTH_OFFICER";
    protected $guarded = [];
    public $timestamps = false;

    // Indicate that the model doesn't have a primary key
    protected $primaryKey = null;
    public $incrementing = false;

    public function branch()
    {
        return $this->hasOne(Branch::class, 'branch_code', 'acct_branch_code');
    }

    public function officerBranch()
    {
        return $this->hasOne(Branch::class, 'branch_code', 'officer_branch_code');
    }
}
