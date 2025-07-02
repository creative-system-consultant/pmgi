<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmgiSummPembiayaanProduk extends Model
{
    use HasFactory;

    protected $table = 'pmgi_summ_pembiayaan_produk';
    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'report_date' => 'date',
        'branch_code' => 'string',
        'product_category' => 'string',
        'PRODUCT_DESC' => 'string',
        'bilakaun' => 'integer',
        'bil_peminjam' => 'integer',
        'jumlah_pembiayaan' => 'float',
    ];
} 