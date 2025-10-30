<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefJttRoles extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table = 'pmgi_ref_jtt_roles';
    protected $guarded = [];

    protected $fillable = [
        'role_name',
        'role_description',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}    
