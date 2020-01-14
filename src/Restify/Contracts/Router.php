<?php

namespace ChicoRei\Packages\Restify\Contracts;

interface Router
{
    /**
     * Register routes for creating and automatic REST API, with the given resource.
     *
     * @param string $name Route path
     * @param array $options Options.
     * @param null $transformer Custom transformer, to override Restify configuration.
     * @return array Registered routes.
     */
    public function resource($name, array $options = [], $transformer = null);

    /**
     * Register index routes for listing the given resource name.
     *
     * @param string $name Route path
     * @param array $options Options.
     * @param null $transformer Custom transformer, to override Restify configuration.
     * @return array Registered routes.
     */
    public function listOnly($name, array $options = [], $transformer = null);

    /**
     * Register index and show routes for the given resource name.
     *
     * @param string $name Route path
     * @param array $options Options.
     * @param null $transformer Custom transformer, to override Restify configuration.
     * @return array Registered routes.
     */
    public function readOnly($name, array $options = [], $transformer = null);

    /**
     * Register resource routes, excluding the delete (destroy) option.
     *
     * @param string $name Route path
     * @param array $options Options.
     * @param null $transformer Custom transformer, to override Restify configuration.
     * @return array Registered routes.
     */
    public function exceptDelete($name, array $options = [], $transformer = null);
}