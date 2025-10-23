<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditRefPmgiResult extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table = 'AUDIT.pmgi_ref_pmgi_result';
}
