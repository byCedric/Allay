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

use ByCedric\Allay\Contracts\Exceptions\Handler as ExceptionHandlerContract;
use ByCedric\Allay\Contracts\Exceptions\Manager as ExceptionManagerContract;
use ByCedric\Allay\Contracts\Resource\Manager as ResourceManagerContract;
use ByCedric\Allay\Contracts\Resource\Resolver as ResourceResolverContract;
use ByCedric\Allay\Contracts\Transformers\Transformer as TransformerContract;
use ByCedric\Allay\Exceptions\Manager as ExceptionManager;
use ByCedric\Allay\Providers\AgnosticServiceProvider;
use ByCedric\Allay\Resource\Manager as ResourceManager;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use Mockery;

class AgnosticServiceProviderTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working agnostic service provider instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application      $app (default: null)
     * @return \ByCedric\Allay\Providers\AgnosticServiceProvider
     */
    protected function getInstance(Application $app = null)
    {
        if (!$app) {
            $app = Mockery::mock(Application::class);
        }

        return new AgnosticServiceProvider($app);
    }

    public function testGetConfigFetchesFromIlluminateRepositoryAndReturnsResult()
    {
        $config = Mockery::mock(Repository::class);
        $app = Mockery::mock(Application::class);
        $provider = $this->getInstance($app);

        $app->shouldReceive('make')
            ->once()
            ->with(Repository::class)
            ->andReturn($config);

        $config->shouldReceive('get')
            ->once()
            ->with('test')
            ->andReturn('test');

        $this->assertSame(
            'test',
            $this->callProtectedMethod($provider, 'getConfig', ['test']),
            'Get config did not return the config value.'
        );
    }

    public function testBootCallsAgnosticMethods()
    {
        $provider = Mockery::mock(AgnosticServiceProvider::class)
            ->shouldAllowMockingProtectedMethods()
            ->shouldDeferMissing();

        $provider->shouldReceive('populateExceptionManager')->once();
        $provider->shouldReceive('populateResourceManager')->once();
        $provider->boot();
    }

    public function testRegisterCallsAgnosticMethods()
    {
        $provider = Mockery::mock(AgnosticServiceProvider::class)
            ->shouldAllowMockingProtectedMethods()
            ->shouldDeferMissing();

        $provider->shouldReceive('registerExceptionManager')->once();
        $provider->shouldReceive('registerResourceManager')->once();
        $provider->shouldReceive('bindExceptionManager')->once();
        $provider->shouldReceive('bindResourceManager')->once();
        $provider->shouldReceive('bindResourceResolver')->once();
        $provider->shouldReceive('bindTransformer')->once();
        $provider->register();
    }

    public function testRegisterExceptionManagerRegistersDefaultManagerAsSingleton()
    {
        $app = Mockery::mock(Application::class);
        $provider = $this->getInstance($app);

        $app->shouldReceive('singleton')
            ->once()
            ->with(
                ExceptionManager::class,
                Mockery::on(function ($closure) use ($app) {
                    return $closure($app) instanceof ExceptionManager;
                })
            );

        $this->callProtectedMethod($provider, 'registerExceptionManager');
    }

    public function testRegisterResourceManagerRegistersDefaultManagerAsSingleton()
    {
        $app = Mockery::mock(Application::class);
        $provider = $this->getInstance($app);

        $app->shouldReceive('make')
            ->once()
            ->with(Container::class)
            ->andReturn($app);

        $app->shouldReceive('singleton')
            ->once()
            ->with(
                ResourceManager::class,
                Mockery::on(function ($closure) use ($app) {
                    return $closure($app) instanceof ResourceManager;
                })
            );

        $this->callProtectedMethod($provider, 'registerResourceManager');
    }

    public function testPopulateExceptionManagerRegistersAllHandlersFromConfig()
    {
        $manager = Mockery::mock(ExceptionManagerContract::class);
        $handler = Mockery::mock(ExceptionHandlerContract::class);
        $app = Mockery::mock(Application::class);
        $provider = Mockery::mock(AgnosticServiceProvider::class, [$app])
            ->shouldAllowMockingProtectedMethods()
            ->shouldDeferMissing();

        $app->shouldReceive('make')
            ->once()
            ->with(ExceptionManagerContract::class)
            ->andReturn($manager);

        $provider->shouldReceive('getConfig')
            ->once()
            ->with('allay.exceptions.handlers')
            ->andReturn([
                ExceptionHandlerContract::class,
            ]);

        $app->shouldReceive('make')
            ->once()
            ->with(ExceptionHandlerContract::class)
            ->andReturn($handler);

        $manager->shouldReceive('register')
            ->once()
            ->with($handler);

        $this->callProtectedMethod($provider, 'populateExceptionManager');
    }

    public function testPopulateResourceManagerRegistersAllResourcesFromConfig()
    {
        $manager = Mockery::mock(ResourceManagerContract::class);
        $app = Mockery::mock(Application::class);
        $provider = Mockery::mock(AgnosticServiceProvider::class, [$app])
            ->shouldAllowMockingProtectedMethods()
            ->shouldDeferMissing();

        $app->shouldReceive('make')
            ->once()
            ->with(ResourceManagerContract::class)
            ->andReturn($manager);

        $provider->shouldReceive('getConfig')
            ->once()
            ->with('allay.resources.models')
            ->andReturn([
                'name' => 'resource',
            ]);

        $manager->shouldReceive('register')
            ->once()
            ->with('name', 'resource');

        $this->callProtectedMethod($provider, 'populateResourceManager');
    }

    public function testBindTransformerOptionallyBindsTheTransformerFromConfig()
    {
        $app = Mockery::mock(Application::class);
        $provider = Mockery::mock(AgnosticServiceProvider::class, [$app])
            ->shouldAllowMockingProtectedMethods()
            ->shouldDeferMissing();

        $provider->shouldReceive('getConfig')
            ->once()
            ->with('allay.transformer')
            ->andReturn('test');

        $app->shouldReceive('bindIf')
            ->once()
            ->with(TransformerContract::class, 'test');

        $this->callProtectedMethod($provider, 'bindTransformer');
    }

    public function testBindExceptionManagerOptionallyBindsTheManagerFromConfig()
    {
        $app = Mockery::mock(Application::class);
        $provider = Mockery::mock(AgnosticServiceProvider::class, [$app])
            ->shouldAllowMockingProtectedMethods()
            ->shouldDeferMissing();

        $provider->shouldReceive('getConfig')
            ->once()
            ->with('allay.exceptions.manager')
            ->andReturn('test');

        $app->shouldReceive('bindIf')
            ->once()
            ->with(ExceptionManagerContract::class, 'test');

        $this->callProtectedMethod($provider, 'bindExceptionManager');
    }

    public function testBindResourceManagerOptionallyBindsTheManagerFromConfig()
    {
        $app = Mockery::mock(Application::class);
        $provider = Mockery::mock(AgnosticServiceProvider::class, [$app])
            ->shouldAllowMockingProtectedMethods()
            ->shouldDeferMissing();

        $provider->shouldReceive('getConfig')
            ->once()
            ->with('allay.resources.manager')
            ->andReturn('test');

        $app->shouldReceive('bindIf')
            ->once()
            ->with(ResourceManagerContract::class, 'test');

        $this->callProtectedMethod($provider, 'bindResourceManager');
    }

    public function testBindResourceResolverOptionallyBindsTheResolverFromConfig()
    {
        $app = Mockery::mock(Application::class);
        $provider = Mockery::mock(AgnosticServiceProvider::class, [$app])
            ->shouldAllowMockingProtectedMethods()
            ->shouldDeferMissing();

        $provider->shouldReceive('getConfig')
            ->once()
            ->with('allay.resources.resolver')
            ->andReturn('test');

        $app->shouldReceive('bindIf')
            ->once()
            ->with(ResourceResolverContract::class, 'test');

        $this->callProtectedMethod($provider, 'bindResourceResolver');
    }
}
