<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmgiSummWilma extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table = 'pmgi_summ_wilma';
    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'report_date' => 'date',
        'bila1' => 'integer',
        'bila2' => 'integer',
        'bila3' => 'integer',
        'bilb1' => 'integer',
        'bilb2' => 'integer',
        'bilc1' => 'integer',
        'bilc2' => 'integer',
        'bild' => 'integer',
        'jumlah' => 'integer',
        'bilnpf' => 'integer',
        'pctnpf' => 'float',
        'pctjumnpf' => 'float',
    ];
} 