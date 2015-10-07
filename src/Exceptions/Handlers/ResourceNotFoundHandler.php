<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Exceptions\Handlers;

use ByCedric\Allay\Exceptions\ResourceNotFoundException;
use Illuminate\Http\Response;

class ResourceNotFoundHandler implements \ByCedric\Allay\Contracts\Exceptions\Handler
{
    /**
     * Determine if the handler is capable of handling the given exception.
     *
     * @param  \Exception $error
     * @return boolean
     */
    public function capable(\Exception $error)
    {
        return $error instanceof ResourceNotFoundException;
    }

    /**
     * Handle the given exception to return a valid response.
     *
     * @param  \Exception $error
     * @return \Illuminate\Http\Response
     */
    public function handle(\Exception $error)
    {
        return new Response([
            'detail' => "The (requested) resource \"{$error->getResource()}\" was not found.",
        ], 404);
    }
}
