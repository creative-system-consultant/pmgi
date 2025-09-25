<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ref_pmgi_Result extends Model
{
    use HasFactory;
    
    protected $table = 'pmgi_ref_pmgi_result';

    protected $fillable = [
        'pmgi_result_desc',
        'updated_at',
        'updated_by',
    ];
}
