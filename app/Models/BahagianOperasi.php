<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahagianOperasi extends Model
{
    use HasFactory;

    protected $table="pmgi_jpoc";
    protected $guarded = [];

    public $timestamps = false;
}
