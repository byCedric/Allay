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

use ByCedric\Allay\Exceptions\ResourceException;

class ResourceExceptionTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working instance of the resource exception.
     *
     * @param  string                                       $name (default: 'test')
     * @return \ByCedric\Allay\Exceptions\ResourceException
     */
    protected function getInstance($name = 'test')
    {
        return new ResourceException($name);
    }

    public function testGetResourceReturnsRequestedResourceName()
    {
        $this->assertSame(
            'test',
            $this->getInstance('test')->getResource(),
            'Exception did not return the correct resource name.'
        );
    }
}
