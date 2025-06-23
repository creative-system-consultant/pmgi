<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrdInfo extends Model
{
    use HasFactory;

    protected $table="pmgi_hrd_info";
    protected $guarded = [];

    public $timestamps = false;
}
