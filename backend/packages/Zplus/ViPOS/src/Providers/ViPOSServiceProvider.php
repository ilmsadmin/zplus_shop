<?php

namespace Zplus\ViPOS\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ViPOSServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'vipos');
        
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'vipos');
        
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        
        // Publish assets
        $this->publishes([
            __DIR__.'/../Resources/assets' => public_path('packages/Zplus/ViPOS/assets'),
        ], 'vipos-assets');
    }

    /**
     * Register package config.
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/menu.php',
            'menu.admin'
        );
    }
}
