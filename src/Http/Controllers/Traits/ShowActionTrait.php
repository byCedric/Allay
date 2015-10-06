<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Http\Controllers\Traits;

use ByCedric\Allay\Contracts\Resource\Manager;
use Illuminate\Http\Request;

trait ShowActionTrait
{
    /**
     * Display a single resource, that matches the requested id.
     *
     * @param  \Illuminate\Http\Request                   $request
     * @param  \ByCedric\Allay\Contracts\Resource\Manager $manager
     * @return void
     */
    public function show(Request $request, Manager $manager)
    {
        $route = $request->route();
        $resource = $manager->make($route->parameter('resource'));

        return $resource->findOrFail($route->parameter('id'));
    }
}
