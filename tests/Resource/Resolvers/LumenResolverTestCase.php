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

class LumenResolverTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working instance of the lumen resource resolver.
     *
     * @param  \Illuminate\Http\Request $request (default: null)
     * @return \ByCedric\Allay\Resource\Resolvers\LumenResolver
     */
    protected function getInstance(Request $request = null)
    {
        if (!$request) {
            $request = Mockery::mock(Request::class);
        }

        return new LumenResolver($request);
    }

    public function testGetRouteParameterReturnsParameterValueFromRoute()
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('route')
            ->once()
            ->andReturn([
                'don\'t know about this',
                function () { /* closure */ },
                ['resource' => 'test']
            ]);

        $resolver = $this->getInstance($request);

        $this->assertSame(
            'test',
            $this->callProtectedMethod($resolver, 'getRouteParameter', ['resource']),
            'Resolver did not return the correct route parameter value.'
        );
    }

    public function testGetRouteParameterReturnsEmptyWhenNotFound()
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('route')
            ->once()
            ->andReturn([
                'don\'t know about this',
                function () { /* closure */ },
                ['resource' => 'test']
            ]);

        $resolver = $this->getInstance($request);

        $this->assertEmpty(
            $this->callProtectedMethod($resolver, 'getRouteParameter', ['abc']),
            'Resolver did not return empty value for unknown route parameter.'
        );
    }
}
