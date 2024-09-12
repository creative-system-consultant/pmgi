<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefEvalPctg extends Model
{
    use HasFactory;
    protected $table = "PMGI_REF_EVAL_PCTG";
    protected $guarded = [];
    public $timestamps = false;

    public function bnmState()
    {
        return $this->hasOne(BnmStatecode::class, 'code', 'state_code');
    }
}
