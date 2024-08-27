<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettPymPmc extends Model
{
    use HasFactory;

    protected $table="PMGI_SETT_PYM_PMC";
    protected $guarded = [];

    public function pyd()
    {
        return $this->belongsTo(User::class, 'pyd_id', 'userid');
    }

    public function pym()
    {
        return $this->belongsTo(User::class, 'pym_id', 'userid');
    }

    public function pmc()
    {
        return $this->belongsTo(User::class, 'pmc_id', 'userid');
    }

    public function info()
    {
        return $this->hasOne(SessionInfo::class, 'session_id', 'session_id');
    }
}
