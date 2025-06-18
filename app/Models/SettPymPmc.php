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
        return $this->belongsTo(User::class, 'PYD_ID', 'USERID');
    }

    public function pym()
    {
        return $this->belongsTo(User::class, 'PYM_ID', 'USERID');
    }

    public function pmc()
    {
        return $this->belongsTo(User::class, 'PMC_ID', 'USERID');
    }

    public function info()
    {
        return $this->hasOne(SessionInfo::class, 'SESSION_ID', 'SESSION_ID');
    }

    public function mntrSession()
    {
        return $this->hasOne(MntrSession::class, 'REPORT_DATE', 'REPORT_DATE')
                    ->where('OFFICER_ID', $this->PYD_ID);
    }
}
