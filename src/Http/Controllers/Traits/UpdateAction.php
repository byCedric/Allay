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
use ByCedric\Allay\Contracts\Resource\Writable;
use ByCedric\Allay\Exceptions\ResourceMissingValidationException;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Http\Request;

trait UpdateAction
{
    /**
     * Update the requested resource entity, storing the provided values.
     *
     * @param  \Illuminate\Http\Request                    $request
     * @param  \ByCedric\Allay\Contracts\Resource\Manager  $manager
     * @param  \ByCedric\Allay\Contracts\Resource\Resolver $resolver
     * @return mixed
     */
    public function update(Request $request, Manager $manager, Resolver $resolver)
    {
        $resource = $manager->make($resolver->getResource());

        if (!$resource instanceof Validatable) {
            throw new ResourceMissingValidationException($resolver->getResource());
        }

        if ($resource instanceof Writable) {
            $resource = $resource->getWritableQuery();
        }

        $resource = $resource->findOrFail($resolver->getId());
        $validator = $resource->fill($request->all())
            ->getValidatorInstance();

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $resource->save();
        return $resource;
    }
}
