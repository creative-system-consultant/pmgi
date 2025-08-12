<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettUalRole extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table="pmgi_sett_ual_role";
    protected $guarded = [];
    public $timestamps = false;

    public function pages()
    {
        return $this->belongsToMany(SettUalPage::class, 'pmgi_sett_ual_role_has_page', 'role_id', 'page_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'pmgi_sett_ual_user_has_role', 'role_id', 'USERID');
    }
}
