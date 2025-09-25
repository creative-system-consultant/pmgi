<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Map_States_hr2fms extends Model
{
    use HasFactory;

    protected $table = 'pmgi_map_states_hr2fms';
 
    public $timestamps = false;

    protected $fillable = [
        'hr_state_name',
    ];
}
