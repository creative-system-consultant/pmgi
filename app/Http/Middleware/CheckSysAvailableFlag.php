<?php

namespace App\Http\Middleware;

use App\Models\GlobalParm;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSysAvailableFlag
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip maintenance check for maintenance route and assets
        if ($this->shouldSkipMaintenanceCheck($request)) {
            return $next($request);
        }

        try {
            $system = GlobalParm::first();

            // Handle case where system config doesn't exist
            if (!$system) {
                return $this->redirectToMaintenance($request);
            }

            // Check if system is available
            if ($system->sys_available_flag !== 'Y') {
                return $this->redirectToMaintenance($request);
            }
        } catch (\Exception $e) {
            return $this->redirectToMaintenance($request);
        }

        return $next($request);
    }

    private function shouldSkipMaintenanceCheck(Request $request): bool
    {
        $skipRoutes = [
            'maintenance',
            'maintenance/*',
        ];

        // Skip for maintenance routes
        if ($request->is($skipRoutes)) {
            return true;
        }

        // Skip for static assets
        if ($request->is('css/*') || $request->is('js/*') || $request->is('images/*')) {
            return true;
        }

        return false;
    }

    /**
     * Redirect to maintenance page with proper handling
     */
    private function redirectToMaintenance(Request $request): Response
    {
        // For AJAX requests, return JSON response
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'System is currently under maintenance.',
                'maintenance' => true,
                'redirect_url' => route('maintenance')
            ], 503);
        }

        // For regular requests, redirect to maintenance page
        return redirect()->route('maintenance')->with([
            'maintenance_message' => 'The system is currently under maintenance. Please try again later.',
            'show_popup' => !$request->is('maintenance') // Show popup only if not already on maintenance page
        ]);
    }
}
