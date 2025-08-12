<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettUalPage extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table="pmgi_sett_ual_page";
    protected $guarded = [];
    public $timestamps = false;

    public function roles()
    {
        return $this->belongsToMany(SettUalRole::class, 'pmgi_sett_ual_role_has_page', 'page_id', 'role_id');
    }
}
