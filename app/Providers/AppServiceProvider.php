<?php

namespace App\Providers;

use App\Models\SettUalPage;
use App\Models\SettUalRoleHasPage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set the locale to Malay globally for Carbon
        Carbon::setLocale('ms_MY');
    }
}
