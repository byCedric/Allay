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
use ByCedric\Allay\Contracts\Resource\Readable;
use ByCedric\Allay\Contracts\Resource\Resolver;

trait IndexAction
{
    /**
     * Display a paginated list of the requested resource.
     *
     * @param  \ByCedric\Allay\Contracts\Resource\Manager  $manager
     * @param  \ByCedric\Allay\Contracts\Resource\Resolver $resolver
     * @return mixed
     */
    public function index(Manager $manager, Resolver $resolver)
    {
        $resource = $manager->make($resolver->getResource());

        if ($resource instanceof Readable) {
            $resource = $resource->getReadableQuery($resource->newQuery());
        }

        return $resource->paginate();
    }
}
