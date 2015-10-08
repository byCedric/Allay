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

use ByCedric\Allay\Contracts\Exceptions\Handler;

class Manager implements \ByCedric\Allay\Contracts\Exceptions\Manager
{
    /**
     * The registered handlers for each exception types.
     *
     * @var array
     */
    protected $handlers = [];

    /**
     * Try to fetch the registered handler for the given exception.
     *
     * @param  string|\Exception                            $error
     * @return \ByCedric\Allay\Contracts\Exceptions\Handler
     */
    protected function getHandler($error)
    {
        foreach ($this->handlers as $handler) {
            if ($handler->capable($error)) {
                return $handler;
            }
        }
    }

    /**
     * Register a new handler, that is capable of converting exceptions to responses.
     *
     * @param  \ByCedric\Allay\Contracts\Exceptions\Handler $handler
     * @return void
     */
    public function register(Handler $handler)
    {
        $this->handlers[] = $handler;
    }

    /**
     * Determine if the given exception manager is capable of handling the exception.
     *
     * @param  \Exception $error
     * @return bool
     */
    public function capable(\Exception $error)
    {
        return !!$this->getHandler($error);
    }

    /**
     * Handle the given exception to return a valid response.
     *
     * @param  \Exception                $error
     * @return \Illuminate\Http\Response
     */
    public function handle(\Exception $error)
    {
        $handler = $this->getHandler($error);

        if ($handler) {
            return $handler->handle($error);
        }
    }
}
