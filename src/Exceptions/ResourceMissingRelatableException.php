<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Exceptions;

use Illuminate\Http\Response;

class ResourceMissingRelatableException extends ResourceException
{
    /**
     * Create a new resource missing relatable exception.
     *
     * @param  string     $resource
     * @param  \Exception $previous (default: null)
     * @return void
     */
    public function __construct($resource, \Exception $previous = null)
    {
        parent::__construct(
            $resource,
            "The resource \"$resource\" has no relatable implementation, making it unavailable for relation calls.",
            Response::HTTP_NOT_IMPLEMENTED,
            $previous
        );
    }
}
