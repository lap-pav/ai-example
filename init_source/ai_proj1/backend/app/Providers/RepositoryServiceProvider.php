<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register User Repository
        $this->app->bind(
            \App\Repositories\UserRepositoryInterface::class,
            function ($app) {
                return new \App\Repositories\UserRepository(
                    new \App\Models\User()
                );
            }
        );

        // Add more repository bindings here as you create them
        // Example pattern:
        // $this->app->bind(
        //     \App\Repositories\{ModelName}RepositoryInterface::class,
        //     function ($app) {
        //         return new \App\Repositories\{ModelName}Repository(
        //             new \App\Models\{ModelName}()
        //         );
        //     }
        // );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
