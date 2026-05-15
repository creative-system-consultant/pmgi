<?php

use Illuminate\Support\Facades\Auth;
use App\Models\SettUalPage;
use App\Models\SettUalRole;
use App\Models\SettUalRoleHasPage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

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
        $userRoleIds = session('user_roles', []);
        if (empty($userRoleIds)) {
            return false;
        }

        // Convert roles to an array if it's not already
        $roles = is_array($roles) ? $roles : [$roles];

        // Cache role mapping so helper doesn't query every call
        $roleMap = Cache::remember('role_name_id_map', now()->addHours(12), function () {
            return \App\Models\SettUalRole::pluck('id', 'name')->toArray();
        });

        static $resolvedRoleIdsMemo = [];
        $memoKey = implode('|', $roles);

        if (!array_key_exists($memoKey, $resolvedRoleIdsMemo)) {
            $resolvedRoleIdsMemo[$memoKey] = collect($roles)
                ->map(function ($role) use ($roleMap) {
                    if (is_numeric($role)) {
                        return (int) $role;
                    }

                    return $roleMap[$role] ?? null;
                })
                ->filter()
                ->values()
                ->all();
        }

        // Check if any of the specified roles match the user's roles
        return !empty(array_intersect($userRoleIds, $resolvedRoleIdsMemo[$memoKey]));
    }
}

if (!function_exists('reportUserRole')) {
    function reportUserRole(): string
    {
        return hasRoles(['URUSETIA HQ', 'ADMINISTRATOR', 'JSM'])
            ? 'admin'
            : 'user';
    }
}

if (!function_exists('reportView')) {
    function reportView(): string
    {
        return !hasRoles(['PYD', 'PYM', 'PMC'])
            ? 'admin'
            : 'user';
    }
}

if (! function_exists('evaluationMonth')) {
    function evaluationMonth($reportDate): string
    {
        $formatReportDate = Carbon::parse($reportDate);
        $startMonth = $formatReportDate->copy()->subMonthNoOverflow()->translatedFormat('F Y');
        $endMonth   = $formatReportDate->copy()->endOfMonth()->translatedFormat('F Y');

        return "$startMonth - $endMonth";
    }
}