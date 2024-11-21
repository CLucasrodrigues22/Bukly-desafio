<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

/**
 * @property Application $app
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerIdeHelper();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }

    /**
     * Register the IDE helper service provider if the application is running in the local environment.
     */
    private function registerIdeHelper(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }
}
