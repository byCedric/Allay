<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Resource\Resolvers;

use Illuminate\Routing\Route;

class LaravelResolver extends AgnosticResolver
{
    /**
     * The route to resolve from.
     *
     * @var \Illuminate\Routing\Route
     */
    protected $route;

    /**
     * Get a new resource resolver instance.
     *
     * @param  \Illuminate\Routing\Route $route
     * @return void
     */
    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    /**
     * Get the value of the given route parameter.
     *
     * @param  string $key
     * @return string
     */
    protected function getRouteParameter($key)
    {
        return $this->route->parameter($key);
    }
}
