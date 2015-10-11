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

use Illuminate\Http\Request;

class LumenResolver extends AgnosticResolver
{
    /**
     * The route parameters to resolve from.
     *
     * @var array
     */
    protected $route;

    /**
     * Get a new resource resolver instance.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $routes = $request->route();
        $this->route = end($routes);
    }

    /**
     * Get the value of the given route parameter.
     *
     * @param  string $key
     * @return string
     */
    protected function getRouteParameter($key)
    {
        if (isset($this->route[$key])) {
            return $this->route[$key];
        }
    }
}
