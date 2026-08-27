<?php

namespace App\Providers;

use App\Services\BillNoSequenceService;
use Illuminate\Support\ServiceProvider;

class BillNoSequenceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(BillNoSequenceService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
