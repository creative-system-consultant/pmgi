<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettUalRole extends Model
{
    use HasFactory;

    protected $table="PMGI_SETT_UAL_ROLE";
    protected $guarded = [];
    public $timestamps = false;

    public function pages()
    {
        return $this->belongsToMany(SettUalPage::class, 'PMGI_SETT_UAL_ROLE_HAS_PAGE', 'ROLE_ID', 'PAGE_ID');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'PMGI_SETT_UAL_USER_HAS_ROLE', 'ROLE_ID', 'USERID');
    }
}
