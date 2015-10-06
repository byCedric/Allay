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

use ByCedric\Allay\Contracts\Exceptions\Handler;
use ByCedric\Allay\Exceptions\Manager;
use Illuminate\Http\Response;
use Mockery;

class ManagerTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working instance of the exception manager.
     *
     * @return \ByCedric\Allay\Exceptions\Manager
     */
    protected function getInstance()
    {
        return new Manager();
    }

    public function testHandlerRegistersAtManager()
    {
        $manager = $this->getInstance();
        $manager->register(Mockery::mock(Handler::class));
    }

    public function testHandlerRegistersAtManagerAndIsDetected()
    {
        $manager = $this->getInstance();
        $handler = Mockery::mock(Handler::class);

        $exception = new \Exception();
        $strangeException = new \RuntimeException();

        $handler->shouldReceive('capable')
            ->with($exception)
            ->andReturn(true);

        $handler->shouldReceive('capable')
            ->with($strangeException)
            ->andReturn(false);

        $manager->register($handler);

        $this->assertTrue($manager->capable($exception), 'Manager did not tell it\'s capable of handling exception.');
        $this->assertFalse(
            $manager->capable($strangeException),
            'Manager wrongly told it was capable of handling the exception.'
        );
    }

    public function testManagerHandlesException()
    {
        $manager = $this->getInstance();
        $handler = Mockery::mock(Handler::class);
        $response = Mockery::mock();

        $exception = new \Exception();
        $strangeException = new \RuntimeException();

        $handler->shouldReceive('capable')
            ->with($exception)
            ->andReturn(true);

        $handler->shouldReceive('capable')
            ->with($strangeException)
            ->andReturn(false);

        $handler->shouldReceive('handle')
            ->with($exception)
            ->andReturn($response);

        $handler->shouldReceive('handle')
            ->with($strangeException)
            ->never();

        $manager->register($handler);

        $this->assertSame($response, $manager->handle($exception), 'Manager did not return correct response.');
        $this->assertEmpty($manager->handle($strangeException), 'Manager did not return null from exception.');
    }

    // public function testManager
}
