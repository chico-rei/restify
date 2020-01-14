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
        $this->app->singleton(RouterContract::class, function ($app) {
            return new RestifyRouter;
        });
    }
}
