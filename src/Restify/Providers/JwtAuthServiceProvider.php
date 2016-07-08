<?php namespace Restify\Providers;

use Illuminate\Config\Repository as Config;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class JwtAuthServiceProvider extends ServiceProvider
{
    /**
     * @inheritdoc
     */
    public function boot(Router $router)
    {
        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param Router $router
     * @param Config $config
     */
    public function map(Router $router, Config $config)
    {
        $router->group(['prefix' => $config->get('restify.prefix', '')], function (Router $router) use ($config)
        {
            $router->post($config->get('restify.jwt.login_route', 'auth/login'), '\Restify\Http\Controllers\AuthController@postLogin');
        });
    }
}
