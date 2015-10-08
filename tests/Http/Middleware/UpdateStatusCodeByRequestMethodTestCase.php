<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Http\Middleware;

use ByCedric\Allay\Http\Middleware\UpdateStatusCodeByRequestMethod;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Mockery;

class UpdateStatusCodeByRequestMethodTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working instance of the status code updating middleware.
     *
     * @param  null|\Illuminate\Contracts\Config\Repository $config (default: null)
     * @return \ByCedric\Allay\Http\Middleware\UpdateStatusCodeByRequestMethod
     */
    public function getInstance(Repository $config = null)
    {
        if (!$config) {
            $config = Mockery::mock(Repository::class);
            $config->shouldReceive('get')
                ->with('allay.statuses')
                ->andReturn(['GET' => 200]);
        }

        return new UpdateStatusCodeByRequestMethod($config);
    }

    public function testMiddlewareReturnsResponse()
    {
        $request = Mockery::mock(Request::class);
        $result = $this->getInstance()->handle($request, function () {
            return 'test';
        });

        $this->assertSame('test', $result, 'Middleware did not return the expected `test` value.');
    }

    public function testMiddlewareDoesNotReplaceModifiedResponseCodes()
    {
        $request = Mockery::mock(Request::class);
        $response = Mockery::mock(Response::class);

        $request->shouldReceive('getMethod')
            ->andReturn('GET');

        $response->shouldReceive('getStatusCode')
            ->once()
            ->andReturn(403);

        $response->shouldReceive('setStatusCode')
            ->never();

        $this->getInstance()->handle($request, function () use ($response) {
            return $response;
        });
    }

    public function testMiddlewareReplacesDefaultStatus()
    {
        $request = Mockery::mock(Request::class);
        $response = Mockery::mock(Response::class);
        $config = Mockery::mock(Repository::class);
        $config->shouldReceive('get')
            ->with('allay.statuses')
            ->andReturn(['PUT' => 204]);

        $middleware = $this->getInstance($config);

        $request->shouldReceive('getMethod')
            ->andReturn('PUT');

        $response->shouldReceive('getStatusCode')
            ->andReturn(200);

        $response->shouldReceive('setStatusCode')
            ->once()
            ->with(204);

        $middleware->handle($request, function () use ($response) {
            return $response;
        });
    }
}
