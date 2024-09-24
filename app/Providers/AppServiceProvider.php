<?php

namespace App\Providers;

use App\Repositories\PromotionRepositoryImpl;
use App\Service\ExportService;
use App\Service\PromotionServiceImpl;
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

        $this->app->singleton('promotionService', function () {
            return new PromotionServiceImpl();
        });

        $this->app->singleton('promotionRepository', function () {
            return new PromotionRepositoryImpl();
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
