<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettUalPage extends Model
{
    use HasFactory;

    protected $table="PMGI_SETT_UAL_PAGE";
    protected $guarded = [];
    public $timestamps = false;

    public function roles()
    {
        return $this->belongsToMany(SettUalRole::class, 'PMGI_SETT_UAL_ROLE_HAS_PAGE', 'PAGE_ID', 'ROLE_ID');
    }
}
