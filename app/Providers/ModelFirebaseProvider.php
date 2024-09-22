<?php

namespace App\Providers;

use App\Models\Promotion;
use App\Models\Referentiel;
use App\Models\UserFirebase;
use App\Repositories\ReferentielRepositoryFirebaseImpl;
use App\Repositories\UserFirebaseRepositoryImpl;
use App\Repositories\UserRepositoryImpl;
use App\Service\UserServiceImpl;
use Illuminate\Support\ServiceProvider;

class ModelFirebaseProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('promotion', function () {
            return new Promotion();
        });

        $this->app->singleton('referentiel', function () {
            return new Referentiel();
        });

        $this->app->singleton('referentielRepository', function () {
            return new ReferentielRepositoryFirebaseImpl();
        });

        $this->app->singleton('userFirebase', function () {
            return new UserFirebase();
        });

        $this->app->singleton('userFirebaseRepository', function () {
            return new UserFirebaseRepositoryImpl();
        });

        $this->app->singleton('userRepository', function () {
            return new UserRepositoryImpl();
        });

        $this->app->singleton('userService', function () {
            return new UserServiceImpl();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
