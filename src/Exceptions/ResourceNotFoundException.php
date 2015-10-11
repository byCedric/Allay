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

class ResourceNotFoundException extends ResourceException
{
    /**
     * Create a new resource not found exception.
     *
     * @param  string     $resource
     * @param  \Exception $previous (default: null)
     * @return void
     */
    public function __construct($resource, \Exception $previous = null)
    {
        parent::__construct(
            $resource,
            "The (requested) resource \"$resource\" was not found.",
            Response::HTTP_NOT_FOUND,
            $previous
        );
    }
}
