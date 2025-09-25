<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefEvalPctg extends Model
{
    use HasFactory;
    
    protected $connection = 'sqlsrv';
    protected $table = "pmgi_ref_eval_pctg";
    protected $guarded = [];
    public $timestamps = false;

    // Mutator to strip leading zero before saving to the database
    public function setStatecodeAttribute($value)
    {
        $this->attributes['state_code'] = ltrim($value, '0');
    }

    // Accessor to strip leading zero when retrieving the value
    public function getStatecodeAttribute($value)
    {
        return ltrim($value, '0');
    }        

    public function bnmState()
    {
        return $this->hasOne(BnmStatecode::class, 'code', 'state_code');
    }
}
