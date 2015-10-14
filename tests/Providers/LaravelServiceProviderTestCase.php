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

use ByCedric\Allay\Contracts\Resource\Resolver;
use ByCedric\Allay\Providers\LaravelServiceProvider;
use ByCedric\Allay\Resource\Resolvers\LaravelResolver;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Mockery;

class LaravelServiceProviderTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working laravel service provider instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application     $app (default: null)
     * @return \ByCedric\Allay\Providers\LaravelServiceProvider
     */
    protected function getInstance(Application $app = null)
    {
        if (!$app) {
            $app = Mockery::mock(Application::class);
        }

        return new LaravelServiceProvider($app);
    }

    public function testGetConfigFileReturnsReadableConfigurationFile()
    {
        $provider = $this->getInstance();

        $this->assertFileExists(
            $this->callProtectedMethod($provider, 'getConfigFile'),
            'Configuration file was not readable.'
        );
    }

    public function testGetConfigPathFetchesPathFromApplicationAndAppendsGivenPath()
    {
        $app = Mockery::mock(Application::class);
        $provider = $this->getInstance($app);

        $app->shouldReceive('make')
            ->atLeast()->once()
            ->with('path.config')
            ->andReturn('test-path');

        $this->assertSame(
            'test-path',
            $this->callProtectedMethod($provider, 'getConfigPath'),
            'Configuration target path was not returned correctly.'
        );

        $this->assertStringEndsWith(
            'target-file.php',
            $this->callProtectedMethod($provider, 'getConfigPath', ['target-file.php']),
            'Configuration target path, with sub-path, was not returned correctly.'
        );
    }

    public function testBootCallsAgnosticMethods()
    {
        $provider = Mockery::mock(LaravelServiceProvider::class)
            ->shouldAllowMockingProtectedMethods()
            ->shouldDeferMissing();

        $provider->shouldReceive('published')->andReturn('irrelevant');
        $provider->shouldReceive('getConfigPath')->andReturn('irrelevant');
        $provider->shouldReceive('initializeResourceRoutes')->andReturn('irrelevant');

        $provider->shouldReceive('populateExceptionManager')->once();
        $provider->shouldReceive('populateResourceManager')->once();

        $provider->boot();
    }

    public function testBootPublishedTheConfiguration()
    {
        $provider = Mockery::mock(LaravelServiceProvider::class)
            ->shouldAllowMockingProtectedMethods()
            ->shouldDeferMissing();

        $provider->shouldReceive('populateExceptionManager')->andReturn('irrelevant');
        $provider->shouldReceive('populateResourceManager')->andReturn('irrelevant');
        $provider->shouldReceive('initializeResourceRoutes')->andReturn('irrelevant');

        $provider->shouldReceive('publishes')
            ->once()
            ->with(Mockery::type('array'));

        $provider->shouldReceive('getConfigPath')
            ->once()
            ->with('allay.php');

        $provider->boot();
    }

    public function testRegisterCallsAgnosticMethods()
    {
        $provider = Mockery::mock(LaravelServiceProvider::class)
            ->shouldAllowMockingProtectedMethods()
            ->shouldDeferMissing();

        $provider->shouldReceive('mergeConfigFrom')->andReturn('irrelevant');

        $provider->shouldReceive('registerExceptionManager')->once();
        $provider->shouldReceive('registerResourceManager')->once();
        $provider->shouldReceive('registerLaravelResourceResolver')->once();
        $provider->shouldReceive('bindExceptionManager')->once();
        $provider->shouldReceive('bindResourceManager')->once();
        $provider->shouldReceive('bindResourceResolver')->once();
        $provider->shouldReceive('bindTransformer')->once();

        $provider->register();
    }

    public function testRegisterMergesConfiguration()
    {
        $provider = Mockery::mock(LaravelServiceProvider::class)
            ->shouldAllowMockingProtectedMethods()
            ->shouldDeferMissing();

        $provider->shouldReceive('registerExceptionManager')->andReturn('irrelevant');
        $provider->shouldReceive('registerResourceManager')->andReturn('irrelevant');
        $provider->shouldReceive('registerLaravelResourceResolver')->andReturn('irrelevant');
        $provider->shouldReceive('bindExceptionManager')->andReturn('irrelevant');
        $provider->shouldReceive('bindResourceManager')->andReturn('irrelevant');
        $provider->shouldReceive('bindResourceResolver')->andReturn('irrelevant');
        $provider->shouldReceive('bindTransformer')->andReturn('irrelevant');

        $provider->shouldReceive('mergeConfigFrom')
            ->once()
            ->with(Mockery::type('string'), 'allay');

        $provider->register();
    }

    public function testInitializeResourceRoutesAppliesConfigurationToRouter()
    {
        $app = Mockery::mock(Application::class);
        $router = Mockery::mock(Router::class);
        $resolver = Mockery::mock(Resolver::class);
        $provider = Mockery::mock(LaravelServiceProvider::class, [$app])
            ->shouldAllowMockingProtectedMethods()
            ->shouldDeferMissing();

        $app->shouldReceive('make')
            ->once()
            ->with(Router::class)
            ->andReturn($router);

        $app->shouldReceive('make')
            ->once()
            ->with(Resolver::class)
            ->andReturn($resolver);

        $resolver->shouldReceive('getResourceParameter')
            ->atLeast()->once()
            ->andReturn('resource');

        $resolver->shouldReceive('getIdParameter')
            ->atLeast()->once()
            ->andReturn('id');

        $provider->shouldReceive('getConfig')
            ->once()
            ->with('allay.routes.controller')
            ->andReturn('My\\Controller');

        $provider->shouldReceive('getConfig')
            ->once()
            ->with('allay.routes.settings')
            ->andReturn(['my' => 'settings']);

        $router->shouldReceive('get')->once()->with('{resource}', 'My\\Controller@index');
        $router->shouldReceive('get')->once()->with('{resource}/{id}', 'My\\Controller@show');
        $router->shouldReceive('post')->once()->with('{resource}', 'My\\Controller@store');
        $router->shouldReceive('put')->once()->with('{resource}/{id}', 'My\\Controller@update');
        $router->shouldReceive('delete')->once()->with('{resource}/{id}', 'My\\Controller@destroy');

        $router->shouldReceive('group')
            ->once()
            ->with(
                ['my' => 'settings'],
                Mockery::on(function ($closure) use ($router) {
                    $closure($router);

                    return true;
                })
            );

        $this->callProtectedMethod($provider, 'initializeResourceRoutes');
    }

    public function testRegisterLaravelResourceResolverRegistersLaravelResolverAsSingleton()
    {
        $request = Mockery::mock(Request::class);
        $app = Mockery::mock(Application::class);
        $provider = $this->getInstance($app);

        $app->shouldReceive('make')
            ->once()
            ->with(Request::class)
            ->andReturn($request);

        $app->shouldReceive('singleton')
            ->once()
            ->with(
                LaravelResolver::class,
                Mockery::on(function ($closure) use ($app) {
                    return $closure($app) instanceof LaravelResolver;
                })
            );

        $this->callProtectedMethod($provider, 'registerLaravelResourceResolver');
    }
}
