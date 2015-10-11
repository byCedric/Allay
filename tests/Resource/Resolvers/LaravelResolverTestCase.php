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

use Illuminate\Routing\Route;
use Mockery;

class LaravelResolverTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working instance of the laravel resource resolver.
     *
     * @param  \Illuminate\Routing\Route $route (default: null)
     * @return \ByCedric\Allay\Resource\Resolvers\LaravelResolver
     */
    protected function getInstance(Route $route = null)
    {
        if (!$route) {
            $route = Mockery::mock(Route::class);
        }

        return new LaravelResolver($route);
    }

    public function testGetRouteParameterReturnsParameterValueFromRoute()
    {
        $route = Mockery::mock(Route::class);
        $resolver = $this->getInstance($route);

        $route->shouldReceive('parameter')
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
