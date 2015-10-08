<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Tests\Exceptions;

use ByCedric\Allay\Exceptions\ResourceNotFoundException;

class ResourceNotFoundExceptionTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working instance of the resource not found exception.
     *
     * @param  string                                               $name (default: 'test')
     * @return \ByCedric\Allay\Exceptions\ResourceNotFoundException
     */
    protected function getInstance($name = 'test')
    {
        return new ResourceNotFoundException($name);
    }

    public function testGetResourceReturnsRequestedResourceName()
    {
        $error = $this->getInstance('my-resource');

        $this->assertSame(
            'my-resource',
            $error->getResource(),
            'Exception did not return the requested resource name.'
        );
    }
}
