<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Tests\Stubs\Resource\Resolvers;

class AgnosticResolver extends \ByCedric\Allay\Resource\Resolvers\AgnosticResolver
{
    /**
     * Get the value of the given route parameter.
     *
     * @param  string $key
     * @return string
     */
    protected function getRouteParameter($key)
    {
        return $key;
    }
}
