<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map_Branches_hr2fms extends Model
{
    use HasFactory;

    protected $table = 'pmgi_map_branches_hr2fms';

    protected $fillable = [
        'seq_no',
        'fms_state_name',
        'fms_branch_name',
        'fms_branch_code',
        'hr_state_name',
        'hr_branch_name',
        'hr_branch_code',
        'upd_flag',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];    
}
