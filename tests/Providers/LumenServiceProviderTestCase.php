<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Tests\Providers;

use ByCedric\Allay\Providers\LumenServiceProvider;
use ByCedric\Allay\Resource\Resolvers\LumenResolver;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Mockery;

class LumenServiceProviderTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working lumen service provider instance.
     *
     * @param  Application                                    $app (default: null)
     * @return \ByCedric\Allay\Providers\LumenServiceProvider
     */
    protected function getInstance(Application $app = null)
    {
        if (!$app) {
            $app = Mockery::mock(Application::class);
        }

        return Mockery::mock(LumenServiceProvider::class, [$app])
            ->shouldAllowMockingProtectedMethods()
            ->shouldDeferMissing();
    }

    public function testGetConfigFileReturnsReadableConfigurationFile()
    {
        $provider = $this->getInstance();

        $this->assertFileExists(
            $this->callProtectedMethod($provider, 'getConfigFile'),
            'Configuration file was not readable.'
        );
    }

    public function testRegisterMergesConfiguration()
    {
        $provider = Mockery::mock(LumenServiceProvider::class)
            ->shouldAllowMockingProtectedMethods()
            ->shouldDeferMissing();

        $provider->shouldReceive('registerExceptionManager')->andReturn('irrelevant');
        $provider->shouldReceive('registerResourceManager')->andReturn('irrelevant');
        $provider->shouldReceive('registerLumenResourceResolver')->andReturn('irrelevant');
        $provider->shouldReceive('bindExceptionManager')->andReturn('irrelevant');
        $provider->shouldReceive('bindResourceManager')->andReturn('irrelevant');
        $provider->shouldReceive('bindResourceResolver')->andReturn('irrelevant');
        $provider->shouldReceive('bindTransformer')->andReturn('irrelevant');

        $provider->shouldReceive('mergeConfigFrom')
            ->once()
            ->with(Mockery::type('string'), 'allay');

        $provider->register();
    }

    public function testRegisterLumenResourceResolverRegistersLumenResolverAsSingleton()
    {
        $request = Mockery::mock(Request::class);
        $app = Mockery::mock(Application::class);
        $provider = $this->getInstance($app);

        $app->shouldReceive('make')
            ->once()
            ->with(Request::class)
            ->andReturn($request);

        $request->shouldReceive('route')
            ->once()
            ->andReturn([
                'still don\'t know about this value',
                function () { /* closure */ },
                ['resource' => 'test'],
            ]);

        $app->shouldReceive('singleton')
            ->once()
            ->with(
                LumenResolver::class,
                Mockery::on(function ($closure) use ($app) {
                    return $closure($app) instanceof LumenResolver;
                })
            );

        $this->callProtectedMethod($provider, 'registerLumenResourceResolver');
    }
}
