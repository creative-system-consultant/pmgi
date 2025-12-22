<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoalanLazimFile extends Model
{
    protected $table = 'AUDIT.pmgi_soalan_lazim';
    protected $connection = 'sqlsrv';

    public $timestamps = true;

    protected $fillable = [
        'officer_lvl',
        'file_name',
        'file_path',
        'uploaded_by',
    ];
}
