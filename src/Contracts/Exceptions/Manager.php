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
     * Register a handler for the given exception type.
     *
     * @param  string                                       $type
     * @param  \ByCedric\Allay\Contracts\Exceptions\Handler $handler
     * @return void
     */
    public function register($type, Handler $handler);

    /**
     * Determine if the given exception has a handler registered.
     *
     * @param  \Exception $type
     * @return boolean
     */
    public function registered($type);

    /**
     * Handle the given exception to return a valid response.
     *
     * @param  \Exception $error
     * @return \Illuminate\Http\Response
     */
    public function handle(\Exception $error);
}
