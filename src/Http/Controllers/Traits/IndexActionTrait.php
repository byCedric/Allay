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

trait IndexActionTrait
{
    /**
     * Display a paginated list of the requested resource.
     *
     * @param  \Illuminate\Http\Request                   $request
     * @param  \ByCedric\Allay\Contracts\Resource\Manager $manager
     * @return void
     */
    public function index(Request $request, Manager $manager)
    {
        $resource = $manager->make(
            $request->route()->parameter('resource')
        );

        return $resource->paginate();
    }
}
