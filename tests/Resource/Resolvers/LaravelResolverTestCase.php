<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Resource\Resolvers;

use Illuminate\Http\Request;
use Mockery;

class LaravelResolverTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working instance of the laravel resource resolver.
     *
     * @param  \Illuminate\Http\Request                           $request (default: null)
     * @return \ByCedric\Allay\Resource\Resolvers\LaravelResolver
     */
    protected function getInstance(Request $request = null)
    {
        if (!$request) {
            $request = Mockery::mock(Request::class);
        }

        return new LaravelResolver($request);
    }

    public function testGetRouteParameterReturnsParameterValueFromRoute()
    {
        $request = Mockery::mock(Request::class);
        $resolver = $this->getInstance($request);

        $request->shouldReceive('route')
            ->once()
            ->with('resource')
            ->andReturn('test');

        $this->assertSame(
            'test',
            $this->callProtectedMethod($resolver, 'getRouteParameter', ['resource']),
            'Resolver did not return the correct route parameter value.'
        );
    }
}
