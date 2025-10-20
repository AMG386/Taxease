<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // <-- add this

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Fix for "Specified key was too long" on older MySQL/MariaDB
        Schema::defaultStringLength(191);
    }
}
