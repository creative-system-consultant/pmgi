<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ref_pmgi_Level extends Model
{
    use HasFactory;
    
    protected $table = 'pmgi_ref_pmgi_level';

    protected $primaryKey = 'pmgi_level';
    public $incrementing = false;
    protected $keyType = 'string';   

    protected $fillable = [
        'pmgi_level_desc',
        'updated_at',
        'updated_by',
    ];   
    
    public function period()
    {
        return $this->hasMany(\App\Models\Ref_pmgi_Period::class, 'pmgi_level', 'pmgi_level');
    }
}
