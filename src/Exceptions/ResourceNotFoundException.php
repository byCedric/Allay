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

class ResourceNotFoundException extends \RuntimeException
{
    /**
     * The requested resource.
     *
     * @var string
     */
    private $resource;

    /**
     * Create a new resource not found exception.
     *
     * @param  string $resource
     * @return void
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    /**
     * Get the requested resource that was not found.
     *
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }
}
