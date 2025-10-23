<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditRefMgrDesc extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table = 'AUDIT.pmgi_ref_mgr_desc';
}
