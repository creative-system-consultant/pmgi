<?php

namespace App\Http\Middleware;

use App\Models\SettUalRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->roles()->count() === 0) {
                $pydRoleId = SettUalRole::where('name', 'PYD')->value('id');
                if ($pydRoleId) {
                    $user->roles()->attach($pydRoleId);
                    $user->load('roles'); // Reload the roles relationship
                }
            }

            // Check if the user has both PYD and either PYM or PMC roles; remove PYD
            $pydRoleId = SettUalRole::where('name', 'PYD')->value('id');
            $pymRoleId = SettUalRole::where('name', 'PYM')->value('id');
            $pmcRoleId = SettUalRole::where('name', 'PMC')->value('id');

            $userRoleIds = $user->roles->pluck('id');

            if ($userRoleIds->contains($pydRoleId)) {
                // Check if the user also has PYM or PMC roles
                if ($userRoleIds->contains($pymRoleId) || $userRoleIds->contains($pmcRoleId)) {
                    // Detach the PYD role and keep the others
                    $user->roles()->detach($pydRoleId);
                    $user->load('roles'); // Reload the roles relationship
                }
            }
        }

        return $next($request);
    }
}
