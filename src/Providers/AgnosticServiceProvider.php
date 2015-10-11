<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Providers;

use ByCedric\Allay\Contracts\Exceptions\Manager as ExceptionManagerContract;
use ByCedric\Allay\Contracts\Resource\Manager as ResourceManagerContract;
use ByCedric\Allay\Contracts\Resource\Resolver as ResourceResolverContract;
use ByCedric\Allay\Contracts\Transformers\Transformer as TransformerContract;
use ByCedric\Allay\Exceptions\Manager as ExceptionManager;
use ByCedric\Allay\Resource\Manager as ResourceManager;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;

class AgnosticServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Get the config value for the provided key.
     *
     * @param  string $key
     * @return mixed
     */
    protected function getConfig($key)
    {
        return $this->app->make(Repository::class)->get($key);
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->populateExceptionManager();
        $this->populateResourceManager();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerExceptionManager();
        $this->registerResourceManager();
        $this->bindExceptionManager();
        $this->bindResourceManager();
        $this->bindResourceResolver();
        $this->bindTransformer();
    }


    /**
     * Register the exception manager, as singleton, to the IoC.
     *
     * @return void
     */
    protected function registerExceptionManager()
    {
        $this->app->singleton(ExceptionManager::class, function () {
            return new ExceptionManager();
        });
    }


    /**
     * Register the resource manager, as singleton, to the IoC.
     *
     * @return void
     */
    protected function registerResourceManager()
    {
        $this->app->singleton(ResourceManager::class, function ($app) {
            return new ResourceManager($app->make(Container::class));
        });
    }

    /**
     * Populate the exception manager, registering all defined handlers.
     *
     * @return void
     */
    protected function populateExceptionManager()
    {
        $manager = $this->app->make(ExceptionManagerContract::class);

        foreach ($this->getConfig('allay.exceptions.handlers') as $handler) {
            $manager->register($this->app->make($handler));
        }
    }

    /**
     * Populate the resource manager, registering all defined models.
     *
     * @return void
     */
    protected function populateResourceManager()
    {
        $manager = $this->app->make(ResourceManagerContract::class);

        foreach ($this->getConfig('allay.resources.models') as $name => $resource) {
            $manager->register($name, $resource);
        }
    }

    /**
     * Bind the exception manager implementation to the abstract contract.
     * Make it optional so the abstraction can be easily modified.
     *
     * @return void
     */
    protected function bindExceptionManager()
    {
        $this->app->bindIf(ExceptionManagerContract::class, $this->getConfig('allay.exceptions.manager'));
    }

    /**
     * Bind the resource manager implementation to the abstract contract.
     * Make it optional so the abstraction can be easily modified.
     *
     * @return void
     */
    protected function bindResourceManager()
    {
        $this->app->bindIf(ResourceManagerContract::class, $this->getConfig('allay.resources.manager'));
    }

    /**
     * Bind the resource resolver implementation to the abstract contract.
     * Make it optional so the abstraction can be easily modified.
     *
     * @return void
     */
    protected function bindResourceResolver()
    {
        $this->app->bindIf(ResourceResolverContract::class, $this->getConfig('allay.resources.resolver'));
    }

    /**
     * Bind the transformer implementation to the abstract contract.
     * Make it optional so the abstraction can be easily modified.
     *
     * @return void
     */
    protected function bindTransformer()
    {
        $this->app->bindIf(TransformerContract::class, $this->getConfig('allay.transformer'));
    }
}
