<?php

namespace ChicoRei\Packages\Restify\Facades;

use ChicoRei\Packages\Restify\Contracts\Router as RouterContract;
use Illuminate\Support\Facades\Facade;

/**
 * Class Router
 *
 * @package ChicoRei\Packages\Restify\Facades
 */
class Router extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return RouterContract::class;
    }
}