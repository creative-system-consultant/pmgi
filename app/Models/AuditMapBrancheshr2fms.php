<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditMapBrancheshr2fms extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv'; 
    protected $table = 'AUDIT.pmgi_map_branches_hr2fms';
}
