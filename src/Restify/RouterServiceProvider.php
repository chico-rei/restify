<?php

namespace ChicoRei\Packages\Restify;

use Illuminate\Support\ServiceProvider;
use ChicoRei\Packages\Restify\Contracts\Router as RouterContract;
use ChicoRei\Packages\Restify\Router as RestifyRouter;

/**
 * Class RouterServiceProvider
 *
 * @package ChicoRei\Packages\Restify
 */
class RouterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/restify.php', 'restify');

        $this->app->singleton(RouterContract::class, function ($app) {
            return new RestifyRouter;
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/restify.php' => $this->app->configPath('restify.php'),
        ], 'restify-config');
    }
}
