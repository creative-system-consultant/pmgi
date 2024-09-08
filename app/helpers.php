<?php

use Illuminate\Support\Facades\Auth;
use App\Models\SettUalPage;
use App\Models\SettUalRole;
use App\Models\SettUalRoleHasPage;

if (!function_exists('hasAccess')) {
    function hasAccess($pageKey)
    {
        // Get access pages from session
        $accessPages = session('user_access_pages', []);

        // Check if the page key exists in the access pages
        return in_array($pageKey, $accessPages);
    }
}

if (!function_exists('hasRoles')) {
    function hasRoles($roles)
    {
        // Get user roles from session
        $userRoles = session('user_roles', []);

        // Convert roles to an array if it's not already
        $roles = is_array($roles) ? $roles : [$roles];

        // Get role IDs for the specified role names or IDs
        $roleIds = SettUalRole::whereIn('name', $roles)->pluck('id')->toArray();

        // Check if any of the specified roles match the user's roles
        return !empty(array_intersect($userRoles, $roleIds));
    }
}
