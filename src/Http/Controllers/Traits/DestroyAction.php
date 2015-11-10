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
use ByCedric\Allay\Contracts\Resource\Resolver;
use ByCedric\Allay\Contracts\Resource\Writable;

trait DestroyAction
{
    /**
     * Delete the requested resource entity, removing it from the database.
     *
     * @param  \ByCedric\Allay\Contracts\Resource\Manager  $manager
     * @param  \ByCedric\Allay\Contracts\Resource\Resolver $resolver
     * @return mixed
     */
    public function destroy(Manager $manager, Resolver $resolver)
    {
        $resource = $manager->make($resolver->getResource());

        if ($resource instanceof Writable) {
            $resource = $resource->getWritableQuery($resource->newQuery());
        }

        $resource = $resource->findOrFail($resolver->getId());
        $resource->delete();

        return $resource;
    }
}
