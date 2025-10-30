<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcpMissingBranch extends Model
{
    use HasFactory;

    protected $table = 'pmgi_excp_missing_branch';
}
