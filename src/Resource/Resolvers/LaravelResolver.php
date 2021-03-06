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

class LaravelResolver extends AgnosticResolver
{
    /**
     * The requestion to resolve the route information with.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Get a new laravel resource resolver instance.
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
        return $this->request->route($key);
    }
}
