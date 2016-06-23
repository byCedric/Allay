<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Resource;

use ByCedric\Allay\Exceptions\ResourceNotFoundException;
use Illuminate\Contracts\Container\Container;

class Manager implements \ByCedric\Allay\Contracts\Resource\Manager
{
    /**
     * The container to spawn the resources from.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * The bound resource, with it's classes.
     *
     * @var array
     */
    protected $resources = [];

    /**
     * Create a new resource manager instance.
     *
     * @param  \Illuminate\Contracts\Container\Container $container
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Register a new resource within the resource manager.
     *
     * @param  string $name
     * @param  string $class
     * @return void
     */
    public function register($name, $class)
    {
        $this->resources[$name] = $class;
    }

    /**
     * Determine if the given resource name has been registered.
     *
     * @param  string $name
     * @return bool
     */
    public function contains($name)
    {
        return isset($this->resources[$name]);
    }

    /**
     * Resolve the given resource name.
     *
     * @param  string                                               $name
     * @throws \ByCedric\Allay\Exceptions\ResourceNotFoundException
     * @return mixed
     */
    public function make($name)
    {
        if ($this->contains($name)) {
            return $this->container->make($this->resources[$name]);
        }

        throw new ResourceNotFoundException($name);
    }

    /**
     * Get the registered name of the resource class.
     *
     * @param  string $class
     * @return string|null
     */
    public function name($class)
    {
        foreach ($this->resources as $name => $model) {
            if ($class == $model || is_subclass_of($class, $model)) {
                return $name;
            }
        }
    }
}
