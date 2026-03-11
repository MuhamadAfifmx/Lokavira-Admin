<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RouteSettingsServiceProvider extends ServiceProvider
{
    /**
     * Tentukan konstanta HOME secara manual di sini
     */
    public const HOME = 'http://admin.lokavira.test/dashboard';

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //
    }
}