<?php

namespace ChicoRei\Packages\Restify;

use ChicoRei\Packages\Restify\Contracts\Router as RouterContract;
use Illuminate\Support\Facades\Facade;

/**
 * Class Restify
 *
 * @package ChicoRei\Packages\Restify
 */
class Restify extends Facade
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