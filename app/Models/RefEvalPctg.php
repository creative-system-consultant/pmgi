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

    public function bnmState()
    {
        return $this->hasOne(BnmStatecode::class, 'code', 'state_code');
    }
}
