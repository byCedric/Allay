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

use ByCedric\Allay\Exceptions\ResourceMissingValidationException;

class ResourceMissingValidationExceptionTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working instance of the resource exception.
     *
     * @param  string                                                        $name (default: 'test')
     * @return \ByCedric\Allay\Exceptions\ResourceMissingValidationException
     */
    protected function getInstance($name = 'test')
    {
        return new ResourceMissingValidationException($name);
    }

    public function testResourceMissingValidationExceptionReturnsMessage()
    {
        $this->assertString(
            $this->getInstance()->getMessage(),
            'Exception did not return a valid message.'
        );
    }

    public function testResourceMissingValidationExceptionReturnsStatusCode()
    {
        $this->assertSame(
            501,
            $this->getInstance()->getCode(),
            'Exception did not return a valid status code.'
        );
    }
}
