<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmgiSummRescheduleInfo extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table = 'pmgi_summ_reschedule_info';
    protected $guarded = [];
    public $timestamps = false;

    // Optionally, add casts for numeric columns if needed
    protected $casts = [
        'report_date' => 'date',
        'terima' => 'integer',
        'lulus' => 'integer',
        'tolak' => 'integer',
        'baki' => 'integer',
        'batal' => 'integer',
        'jana' => 'integer',
        'jumterima' => 'float',
        'jumlulus' => 'float',
        'jumtolak' => 'float',
        'jumbaki' => 'float',
        'jumbatal' => 'float',
    ];
} 