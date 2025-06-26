<?php

namespace Zplus\Warranty\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class WarrantyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        \Log::info('WarrantyServiceProvider register() called');
        $this->registerConfig();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        \Log::info('WarrantyServiceProvider boot() called');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'warranty');
        
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'warranty');
        
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        
        // Publish assets
        $this->publishes([
            __DIR__.'/../Resources/assets' => public_path('packages/Zplus/Warranty/assets'),
        ], 'warranty-assets');
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
        
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/acl.php',
            'acl'
        );
    }
}