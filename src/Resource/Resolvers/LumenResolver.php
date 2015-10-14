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
     * The requestion to resolve the route information with.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Get a new lumen resource resolver instance.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the value of the given route parameter.
     *
     * @param  string $key
     * @return string
     */
    protected function getRouteParameter($key)
    {
        $route = $this->request->route();
        $route = end($route);

        if (isset($route[$key])) {
            return $route[$key];
        }
    }
}
