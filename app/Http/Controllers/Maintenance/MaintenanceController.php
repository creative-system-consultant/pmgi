<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Models\GlobalParm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MaintenanceController extends Controller
{
    public function index()
    {
        return view('maintenance.index');
    }

    /**
     * Check if system is still in maintenance mode (for AJAX polling)
     */
    public function checkStatus()
    {
        // Clear cache to get fresh status
        Cache::forget('sys_available_flag');
        
        $system = GlobalParm::first();
        $available = $system && $system->sys_available_flag === 'Y';

        return response()->json([
            'available' => $available,
            'redirect_url' => $available ? route('login') : null
        ]);
    }
}
