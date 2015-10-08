<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Contracts\Resource;

interface Manager
{
    /**
     * Register a new resource within the resource manager.
     *
     * @param  string $name
     * @param  string $class
     * @return void
     */
    public function register($name, $class);

    /**
     * Determine if the given resource name has been registered.
     *
     * @param  string $name
     * @return boolean
     */
    public function contains($name);

    /**
     * Resolve the given resource name.
     *
     * @param  string $name
     * @return mixed
     */
    public function make($name);
}
