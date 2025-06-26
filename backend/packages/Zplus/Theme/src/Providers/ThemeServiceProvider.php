<?php

namespace Zplus\Theme\Providers;

use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load views from the theme
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'zplus-theme');
        
        // Override shop views with our theme views
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'shop');
        
        // Publish assets
        $this->publishes([
            __DIR__ . '/../Resources/assets' => public_path('themes/shop/zplus'),
        ], 'zplus-theme-assets');
        
        // Override the theme asset helper to use our theme assets
        if (config('themes.shop-default') === 'zplus') {
            view()->addNamespace('shop', [
                __DIR__ . '/../Resources/views',
                resource_path('views')
            ]);
        }
    }
}