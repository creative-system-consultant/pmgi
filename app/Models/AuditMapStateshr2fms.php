<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditMapStateshr2fms extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table = 'AUDIT.pmgi_map_states_hr2fms';    
}
