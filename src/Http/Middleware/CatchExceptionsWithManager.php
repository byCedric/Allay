<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Http\Middleware;

use ByCedric\Allay\Contracts\Exceptions\Manager;

class CatchExceptionsWithManager
{
    /**
     * The exceptions manager to handle the exceptions with.
     *
     * @var \ByCedric\Allay\Contracts\Exceptions\Manager
     */
    protected $manager;

    /**
     * Create a new exceptions catching middleware.
     *
     * @param  \ByCedric\Allay\Contracts\Exceptions\Manager $manager
     * @return void
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Handle all exceptions that can be managed, returning a response object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, \Closure $next)
    {
        try {
            return $next($request);
        } catch (\Exception $error) {
            return $this->handleException($error);
        }
    }

    /**
     * Handle the given exception, if it can't be handled it will be rethrown.
     *
     * @param  \Exception                $error
     * @throws \Exception
     * @return \Illuminate\Http\Response
     */
    protected function handleException(\Exception $error)
    {
        if ($this->manager->capable($error)) {
            return $this->manager->handle($error);
        }

        throw $error;
    }
}
