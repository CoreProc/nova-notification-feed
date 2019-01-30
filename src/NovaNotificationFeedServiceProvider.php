<?php

namespace Coreproc\NovaNotificationFeed;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class NovaNotificationFeedServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nova_notification_feed');

        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            //
        });

        // Add js css by default
        Nova::script('nova-notifications', __DIR__ . '/../dist/js/notification_feed.js');
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
            ->prefix('nova-vendor/nova-notifications')
            ->namespace('Coreproc\NovaNotificationFeed\Http\Controllers')
            ->group(__DIR__ . '/../routes/api.php');

        Route::middleware(['nova'])
            ->prefix('nova-vendor/nova-notifications')
            ->group(__DIR__ . '/../routes/channels.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
