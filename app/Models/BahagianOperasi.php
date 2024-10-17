<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahagianOperasi extends Model
{
    use HasFactory;

    protected $table="PMGI_JPOC";
    protected $guarded = [];

    public $timestamps = false;
}
