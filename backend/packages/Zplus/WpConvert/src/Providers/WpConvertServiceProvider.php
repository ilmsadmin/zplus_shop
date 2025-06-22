<?php

namespace Zplus\WpConvert\Providers;

use Illuminate\Support\ServiceProvider;

class WpConvertServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'wp_convert');
        
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'wp_convert');
    }
}