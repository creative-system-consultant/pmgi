<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MntrSession extends Model
{
    use HasFactory;

    protected $table="PMGI_MNTR_SESSION";
    protected $guarded = [];
    public $timestamps = false;
}
