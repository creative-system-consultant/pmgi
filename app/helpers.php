<?php

use Illuminate\Support\Facades\Auth;
use App\Models\SettUalPage;
use App\Models\SettUalRole;
use App\Models\SettUalRoleHasPage;
use Illuminate\Support\Facades\Redirect;

if (!function_exists('hasAccess')) {
    function hasAccess($pageKey)
    {
        $user = Auth::user();
        $page = SettUalPage::where('key', $pageKey)->first();

        if (!$page) {
            return false;
        }

        // Get all role IDs associated with the user
        $userRoleIds = $user->roles()->pluck('role_id');

        // Check if the user has no roles assigned
        if ($userRoleIds->isEmpty()) {
            // Find the role ID for 'PYD'
            $pydRoleId = SettUalRole::where('name', 'PYD')->value('id');

            if ($pydRoleId) {
                // Assign the 'PYD' role to the user
                $user->roles()->attach($pydRoleId);
                $userRoleIds = collect([$pydRoleId]);
            }
        }

        // Check if any of the user's roles have access to the page
        return SettUalRoleHasPage::whereIn('role_id', $userRoleIds)
            ->where('page_id', $page->id)
            ->exists();
    }
}

if (!function_exists('hasRoles')) {
    function hasRoles($roles)
    {
        $user = Auth::user();

        // Convert the $roles parameter to an array if it's not already
        $roles = is_array($roles) ? $roles : [$roles];

        // Get the user's role IDs
        $userRoleIds = $user->roles()->pluck('role_id')->toArray();

        // Get the role IDs for the specified role names or IDs
        $roleIds = SettUalRole::whereIn('name', $roles)
            ->pluck('id')
            ->toArray();

        // Check if the user has any of the specified roles
        return !empty(array_intersect($userRoleIds, $roleIds));
    }
}
