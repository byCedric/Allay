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

use ByCedric\Allay\Providers\LaravelServiceProvider;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use Mockery;

class LaravelServiceProviderTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working laravel service provider instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app (default: null)
     * @return \ByCedric\Allay\Providers\LaravelServiceProvider
     */
    public function getInstance(Application $app = null)
    {
        if (!$app) {
            $app = Mockery::mock(Application::class);
        }

        return Mockery::mock(LaravelServiceProvider::class, [$app])
            ->shouldAllowMockingProtectedMethods()
            ->shouldDeferMissing();
    }

    public function testGetConfigFetchesFromIlluminateRepository()
    {
        $app = Mockery::mock(Application::class);
        $config = Mockery::mock(Repository::class);
        $provider = $this->getInstance($app);

        $app->shouldReceive('make')
            ->once()
            ->with(Repository::class)
            ->andReturn($config);

        $config->shouldReceive('get')
            ->once()
            ->with('test')
            ->andReturn('result');

        $this->assertSame(
            'result',
            $this->callProtectedMethod($provider, 'getConfig', ['test']),
            'Configuration was not returned correctly.'
        );
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
        $provider = $this->getInstance();
        $provider->shouldReceive('published')->andReturn('irrelevant');
        $provider->shouldReceive('getConfigPath')->andReturn('irrelevant');
        $provider->shouldReceive('initializeResourceRoutes')->andReturn('irrelevant');

        $provider->shouldReceive('populateExceptionManager')->once();
        $provider->shouldReceive('populateResourceManager')->once();

        $provider->boot();
    }

    public function testBootPublishedTheConfiguration()
    {
        $provider = $this->getInstance();
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
        $provider = $this->getInstance();
        $provider->shouldReceive('mergeConfigFrom')->andReturn('irrelevant');

        $provider->shouldReceive('registerExceptionManager')->once();
        $provider->shouldReceive('registerResourceManager')->once();
        $provider->shouldReceive('bindExceptionManager')->once();
        $provider->shouldReceive('bindResourceManager')->once();
        $provider->shouldReceive('bindTransformer')->once();

        $provider->register();
    }

    public function testRegisterMergesConfiguration()
    {
        $provider = $this->getInstance();
        $provider->shouldReceive('registerExceptionManager')->andReturn('irrelevant');
        $provider->shouldReceive('registerResourceManager')->andReturn('irrelevant');
        $provider->shouldReceive('bindExceptionManager')->andReturn('irrelevant');
        $provider->shouldReceive('bindResourceManager')->andReturn('irrelevant');
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
        $provider = $this->getInstance($app);

        $app->shouldReceive('make')
            ->once()
            ->with(Router::class)
            ->andReturn($router);

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
}
