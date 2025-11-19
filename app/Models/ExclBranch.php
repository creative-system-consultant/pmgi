<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExclBranch extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'pmgi_excl_branch';
    public $timestamps = true;
    protected $fillable = [
        'state_code',
        'state_name',
        'branch_code',
        'branch_name',
        'created_by',
        'updated_by'
    ];
}
