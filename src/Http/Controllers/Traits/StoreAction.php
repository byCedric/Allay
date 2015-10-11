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
use ByCedric\Allay\Contracts\Resource\Validatable;
use ByCedric\Allay\Exceptions\ResourceMissingValidationException;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Http\Request;

trait StoreAction
{
    /**
     * Create a new entity of the requested resource, with the given attributes.
     *
     * @param  \Illuminate\Http\Request                    $request
     * @param  \ByCedric\Allay\Contracts\Resource\Manager  $manager
     * @param  \ByCedric\Allay\Contracts\Resource\Resolver $resolver
     * @return mixed
     */
    public function store(Request $request, Manager $manager, Resolver $resolver)
    {
        $resource = $manager->make($resolver->getResource());

        if (!$resource instanceof Validatable) {
            throw new ResourceMissingValidationException($resolver->getResource());
        }

        $validator = $resource->fill($request->all())
            ->getValidatorInstance();

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $resource->save();

        return $resource;
    }
}
