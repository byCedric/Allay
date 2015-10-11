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

use ByCedric\Allay\Contracts\Transformers\Transformer;
use ByCedric\Allay\Http\Middleware\TransformResponseToArray;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery;

class TransformResponseToArrayTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working instance of the content transforming middleware.
     *
     * @param  null|\ByCedric\Allay\Contracts\Transformers\Transformer  $transformer (default: null)
     * @return \ByCedric\Allay\Http\Middleware\TransformResponseToArray
     */
    protected function getInstance(Transformer $transformer = null)
    {
        if (!$transformer) {
            $transformer = Mockery::mock(Transformer::class);
        }

        return new TransformResponseToArray($transformer);
    }

    public function testMiddlewareReturnsResponse()
    {
        $request = Mockery::mock(Request::class);
        $response = $this->getInstance()->handle($request, function () {
            return 'test';
        });

        $this->assertSame('test', $response, 'Middleware did not respond with the expected response.');
    }

    public function testMiddlewareTransformsContentOfTheResponse()
    {
        $request = Mockery::mock(Request::class);
        $response = Mockery::mock(Response::class);
        $transformer = Mockery::mock(Transformer::class);
        $middleware = $this->getInstance($transformer);

        $response->shouldReceive('getOriginalContent')
            ->once()
            ->andReturn('test');

        $response->shouldReceive('getStatusCode')
            ->once()
            ->andReturn(200);

        $transformer->shouldReceive('transform')
            ->once()
            ->with('test', 200)
            ->andReturn(['data' => 'test']);

        $response->shouldReceive('setContent')
            ->once()
            ->with(['data' => 'test']);

        $result = $middleware->handle($request, function () use ($response) {
            return $response;
        });

        $this->assertSame($response, $result, 'Middleware did not respond with the modified response.');
    }
}
