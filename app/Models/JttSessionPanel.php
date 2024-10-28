<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JttSessionPanel extends Model
{
    use HasFactory;

    protected $table="PMGI_JTT_SESSION_PANEL";
    protected $guarded = [];
    public $timestamps = false;
}
