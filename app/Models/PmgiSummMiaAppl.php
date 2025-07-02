<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmgiSummMiaAppl extends Model
{
    use HasFactory;

    protected $table = 'pmgi_summ_mia_appl';
    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'report_date' => 'date',
        'jumlah' => 'integer',
        'lulus' => 'integer',
        'proses' => 'integer',
        'tolak' => 'integer',
        'dikembalikan' => 'integer',
    ];
} 