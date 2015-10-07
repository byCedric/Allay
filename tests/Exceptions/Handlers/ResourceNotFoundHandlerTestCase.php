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
use Mockery;

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
            Mockery::mock(ResourceNotFoundException::class),
            'Handler was not capable of handling designated exception.'
        );
    }

    public function testCapableReturnsFalseWhenOtherExceptionsAreProvided()
    {
        $this->assertIsNotCapable(
            $this->getInstance(),
            Mockery::mock(\RuntimeException::class),
            'Handler was capable of "strange" exception.'
        );
    }

    public function testHandleReturnsResponseWithCorrectStatus()
    {
        $error = Mockery::mock(ResourceNotFoundException::class);
        $error->shouldReceive('getResource')
            ->atLeast()->once()
            ->andReturn('test');

        $this->assertHandlesToResponse($this->getInstance(), $error, 404, 'Handler returned a malformed response.');
    }
}
