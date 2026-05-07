<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;          // ← add this
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Use Bootstrap 5 pagination views everywhere
        Paginator::useBootstrapFive();
    }
}