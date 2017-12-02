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

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;

class ValidationHandler implements \ByCedric\Allay\Contracts\Exceptions\Handler
{
    /**
     * Determine if the handler is capable of handling the given exception.
     *
     * @param  \Exception $error
     * @return bool
     */
    public function capable(\Exception $error)
    {
        return $error instanceof ValidationException;
    }

    /**
     * Handle the given exception to return a valid response.
     *
     * @param  \Exception                $error
     * @return \Illuminate\Http\Response
     */
    public function handle(\Exception $error)
    {
        $errors = array_map(function ($error) {
            return ['detail' => $error];
        }, $error->errors());

        return new Response($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
