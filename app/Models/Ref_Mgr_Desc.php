<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ref_Mgr_Desc extends Model
{
    use HasFactory;
    
    protected $table = 'pmgi_ref_mgr_desc';

    protected $fillable = [
        'seq_no',
        'mgr_desc',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];
}
