<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettStateCommittee extends Model
{
    use HasFactory;

    protected $table="PMGI_SETT_STATE_COMMITTEE";
    protected $guarded = [];
    public $timestamps = false;

    public function state()
    {
        return $this->belongsTo(BnmStatecode::class, 'statecode', 'code');
    }
}
