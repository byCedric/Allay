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
use ByCedric\Allay\Contracts\Resource\Relatable;
use ByCedric\Allay\Contracts\Resource\Resolver;
use ByCedric\Allay\Exceptions\ResourceMissingRelatableException;
use ByCedric\Allay\Exceptions\ResourceRelationNotFoundException;

trait RelatedIndexAction
{
    /**
     * Display a paginated list of the relation of the requested resource.
     *
     * @param  \ByCedric\Allay\Contracts\Resource\Manager                   $manager
     * @param  \ByCedric\Allay\Contracts\Resource\Resolver                  $resolver
     * @throws \ByCedric\Allay\Exceptions\ResourceMissingRelatableException
     * @throws \ByCedric\Allay\Exceptions\ResourceRelationNotFoundException
     * @return mixed
     */
    public function relatedIndex(Manager $manager, Resolver $resolver)
    {
        $resource = $manager->make($resolver->getResource());

        if (!$resource instanceof Relatable) {
            throw new ResourceMissingRelatableException($resource);
        }

        $method = $resolver->getRelationMethod();
        $allowed = $resource->getRelatableMethods();

        if (!in_array($method, $allowed) || !is_callable([$resource, $method])) {
            throw new ResourceRelationNotFoundException(
                $resolver->getResource(),
                $resolver->getRelation()
            );
        }

        if ($resource instanceof Readable) {
            $resource = $resource->getReadableQuery($resource->newQuery());
        }

        $resource = $resource->findOrFail($resolver->getId());
        $relation = $resource->$method();
        $relatedResource = $relation->getRelated();

        if ($relatedResource instanceof Readable) {
            $relation = $relatedResource->getReadableQuery($relation->getQuery());
        }

        return $relation->paginate();
    }
}
