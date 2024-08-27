<?php

namespace App\Http\Middleware;

use App\Models\SettUalPage;
use App\Models\SettUalRoleHasPage;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $pageKey): Response
    {
        $user = Auth::user();

        // Find the page by the key
        $page = SettUalPage::where('key', $pageKey)->first();

        if (!$page) {
            abort(401, 'Unauthorized Access. Page not found.');
        }

        // Get all role IDs associated with the user
        $userRoleIds = $user->roles()->pluck('role_id');

        // Check if any of the user's roles have access to the page
        $hasAccess = SettUalRoleHasPage::whereIn('role_id', $userRoleIds)
            ->where('page_id', $page->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
