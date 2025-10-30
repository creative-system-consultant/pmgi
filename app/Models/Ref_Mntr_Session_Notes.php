<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ref_Mntr_Session_Notes extends Model
{
    use HasFactory;
    
    protected $table = 'pmgi_ref_mntr_session_notes';

    protected $fillable = [
        'sesn_note_desc',
        'updated_at',
        'updated_by',
    ];
}
