<?php

namespace App\Providers;

use App\Service\ExportService;
use App\Service\ReferentielServiceImpl;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('export', function () {
            return new ExportService();
        });

        $this->app->singleton('referentielService', function () {
            return new ReferentielServiceImpl();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
