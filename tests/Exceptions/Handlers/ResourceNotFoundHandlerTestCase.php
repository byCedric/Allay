<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Tests\Exceptions\Handlers;

use ByCedric\Allay\Exceptions\Handlers\ResourceNotFoundHandler;
use ByCedric\Allay\Exceptions\ResourceNotFoundException;

class ResourceNotFoundHandlerTestCase extends \ByCedric\Allay\Tests\ExceptionHandlerTestCase
{
    /**
     * Get a working instance of the resource not found handler.
     *
     * @return \ByCedric\Allay\Exceptions\Handlers\ResourceNotFoundHandler
     */
    protected function getInstance()
    {
        return new ResourceNotFoundHandler;
    }

    public function testCapableReturnsTrueWhenResourceExceptionWasProvided()
    {
        $this->assertIsCapable(
            $this->getInstance(),
            new ResourceNotFoundException('test'),
            'Handler was not capable of handling designated exception.'
        );
    }

    public function testCapableReturnsFalseWhenOtherExceptionsAreProvided()
    {
        $this->assertIsNotCapable(
            $this->getInstance(),
            new \RuntimeException,
            'Handler was capable of "strange" exception.'
        );
    }

    public function testHandleReturnsResponseWithCorrectStatus()
    {
        $this->assertHandlesToResponse(
            $this->getInstance(),
            new ResourceNotFoundException('test'),
            404,
            'Handler returned a malformed response.'
        );
    }
}
