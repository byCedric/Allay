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

class ResourceException extends \RuntimeException
{
    /**
     * The resource name of this exception.
     *
     * @var string
     */
    private $resource;

    /**
     * Create a new resource exception.
     *
     * @param  string     $resource
     * @param  string     $message  (default: '')
     * @param  int        $code     (default: 0)
     * @param  \Exception $previous (default: null)
     * @return void
     */
    public function __construct($resource, $message = '', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->resource = $resource;
    }

    /**
     * Get the resource name of this exception.
     *
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }
}
