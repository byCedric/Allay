<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Contracts\Exceptions;

interface Manager
{
    /**
     * Register a new handler, that is capable of converting exceptions to responses.
     *
     * @param  \ByCedric\Allay\Contracts\Exceptions\Handler $handler
     * @return void
     */
    public function register(Handler $handler);

    /**
     * Determine if the given exception manager is capable of handling the exception.
     *
     * @param  \Exception $error
     * @return boolean
     */
    public function capable(\Exception $error);

    /**
     * Handle the given exception to return a valid response.
     *
     * @param  \Exception $error
     * @return \Illuminate\Http\Response
     */
    public function handle(\Exception $error);
}
