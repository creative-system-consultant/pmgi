<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ref_pmgi_Period extends Model
{
    use HasFactory;
    protected $table = 'pmgi_ref_pmgi_period';

    protected $primaryKey = 'pmgi_level';
    public $incrementing = false; // only if it’s not auto-increment
    protected $keyType = 'string';   

    protected $fillable = [
        'effective_date',
        'wait_period',
        'pmgi_level',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];  
    
    public function level()
    {
        return $this->belongsTo(\App\Models\Ref_pmgi_Level::class, 'pmgi_level', 'pmgi_level');
    }
}