<?php

namespace ChicoRei\Packages\Restify;

use ChicoRei\Packages\Restify\Contracts\Router as RouterContract;
use ChicoRei\Packages\Restify\Http\Controllers\ResourceController;
use Illuminate\Support\Facades\Route;

class Router implements RouterContract
{
    /**
     * @inheritDoc
     */
    public function resource($name, array $options = [], $transformer = null)
    {
        $pendingRegRoute = Route::resource($name, '\\'.ResourceController::class, array_merge(['except' => ['create', 'edit']], $options))->names([
            'index' => '', 'create' => '', 'store' => '', 'show' => '', 'edit' => '', 'update' => '', 'destroy' => ''
        ]);

        return array_map(function ($route) use ($transformer) {
            /** @var \Illuminate\Routing\Route $route */
            return $route->defaults('restify.transformer', $transformer);
        }, $pendingRegRoute->register()->get());
    }

    /**
     * @inheritDoc
     */
    public function listOnly($name, array $options = [], $transformer = null)
    {
        unset($options['except'], $options['only']);
        $options['only'] = ['index'];

        $this->resource($name, $options, $transformer);
    }

    /**
     * @inheritDoc
     */
    public function readOnly($name, array $options = [], $transformer = null)
    {
        unset($options['except'], $options['only']);
        $options['only'] = ['index', 'show'];

        $this->resource($name, $options, $transformer);
    }

    /**
     * @inheritDoc
     */
    public function exceptDelete($name, array $options = [], $transformer = null)
    {
        unset($options['except'], $options['only']);
        $options['except'] = ['destroy'];

        $this->resource($name, $options, $transformer);
    }
}