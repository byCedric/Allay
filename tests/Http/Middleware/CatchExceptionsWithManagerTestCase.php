<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Tests\Http\Middleware;

use ByCedric\Allay\Contracts\Exceptions\Manager;
use ByCedric\Allay\Http\Middleware\CatchExceptionsWithManager;
use Illuminate\Http\Request;
use Mockery;

class CatchExceptionsWithManagerTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working instance of the error catching middleware.
     *
     * @param  null|\ByCedric\Allay\Contracts\Exceptions\Manager $manager (default: null)
     * @return \ByCedric\Allay\Http\Middleware\CatchExceptionsWithManager
     */
    public function getInstance(Manager $manager = null)
    {
        if (!$manager) {
            $manager = Mockery::mock(Manager::class);
        }

        return new CatchExceptionsWithManager($manager);
    }

    public function testMiddlewareReturnsResponse()
    {
        $request = Mockery::mock(Request::class);
        $result = $this->getInstance()->handle($request, function () {
            return 'test';
        });

        $this->assertSame('test', $result, 'Middleware did not return the expected `test` value.');
    }

    public function testMiddlewareReturnsResponseWhenExceptionWasThrown()
    {
        $request = Mockery::mock(Request::class);
        $manager = Mockery::mock(Manager::class);
        $middleware = $this->getInstance($manager);
        $exception = new \InvalidArgumentException();

        $manager->shouldReceive('capable')
            ->once()
            ->with($exception)
            ->andReturn(true);

        $manager->shouldReceive('handle')
            ->once()
            ->with($exception)
            ->andReturn('test');

        $result = $middleware->handle($request, function () use ($exception) {
            throw $exception;
        });

        $this->assertSame('test', $result, 'Middleware did not respond with the converted exception response.');
    }

    public function testMiddlewareRethrowsExceptionWhenExceptionWasThrown()
    {
        $request = Mockery::mock(Request::class);
        $manager = Mockery::mock(Manager::class);
        $middleware = $this->getInstance($manager);
        $exception = new \RuntimeException();

        $manager->shouldReceive('capable')
            ->once()
            ->with($exception)
            ->andReturn(false);

        try {
            $middleware->handle($request, function () use ($exception) {
                throw $exception;
            });
        } catch (\RuntimeException $error) {
            return; // stop the test, exception was thrown
        }

        $this->fail('No exception was (re)thrown from the middleware.');
    }
}
