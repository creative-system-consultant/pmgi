<?php

namespace App\Http\Middleware;

use App\Models\SettUalPage;
use App\Models\SettUalRole;
use App\Models\SettUalRoleHasPage;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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
            $user->loadMissing('roles');
            $userRoleIds = $user->roles->pluck('id')->toArray();

            $roleIdsByName = SettUalRole::whereIn('name', ['PYD', 'PYM', 'PMC'])
                ->pluck('id', 'name');
            $pydRoleId = $roleIdsByName->get('PYD');
            $pymRoleId = $roleIdsByName->get('PYM');
            $pmcRoleId = $roleIdsByName->get('PMC');

            if (empty($userRoleIds)) {
                if ($pydRoleId) {
                    $user->roles()->attach($pydRoleId);
                    $user->load('roles'); // Reload the roles relationship
                    $userRoleIds = $user->roles->pluck('id')->toArray();
                }
            }

            if (in_array($pydRoleId, $userRoleIds, true)) {
                // Check if the user also has PYM or PMC roles
                if (in_array($pymRoleId, $userRoleIds, true) || in_array($pmcRoleId, $userRoleIds, true)) {
                    // Detach the PYD role and keep the others
                    $user->roles()->detach($pydRoleId);
                    $user->load('roles'); // Reload the roles relationship
                    $userRoleIds = $user->roles->pluck('id')->toArray();
                }
            }

            // Refresh the access pages and roles stored in the session
            $accessPages = SettUalPage::select('key')
                ->whereIn('id', SettUalRoleHasPage::whereIn('role_id', $userRoleIds)
                                ->pluck('page_id'))
                ->pluck('key')
                ->toArray();

            // Store the updated access pages and roles in the session
            Session::put('user_access_pages', $accessPages);
            Session::put('user_roles', $userRoleIds);
        }

        return $next($request);
    }
}
