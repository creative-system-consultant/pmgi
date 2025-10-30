<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditMntrSession extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table = 'AUDIT.pmgi_ref_mntr_session_notes';        
}
