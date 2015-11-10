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

use ByCedric\Allay\Contracts\Resource\Resolver;
use ByCedric\Allay\Resource\Resolvers\LaravelResolver;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

class LaravelServiceProvider extends AgnosticServiceProvider
{
    /**
     * Get the package's configuration file.
     *
     * @return string
     */
    protected function getConfigFile()
    {
        return __DIR__.'/../Config/LaravelConfig.php';
    }

    /**
     * Get the project's configuration path, to `publish` the configuration file to.
     *
     * @param  string $path (default: '')
     * @return string
     */
    protected function getConfigPath($path = '')
    {
        return $this->app->make('path.config').($path ? DIRECTORY_SEPARATOR.$path : '');
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->initializeResourceRoutes();
        $this->publishes([
            $this->getConfigFile() => $this->getConfigPath('allay.php'),
        ]);
    }

    /**
     * Boot all resource actions and their routes.
     *
     * @return void
     */
    protected function initializeResourceRoutes()
    {
        $router = $this->app->make(Router::class);
        $resolver = $this->app->make(Resolver::class);

        $controller = $this->getConfig('allay.routes.controller');
        $settings = $this->getConfig('allay.routes.settings');

        $router->group($settings, function ($router) use ($controller, $resolver) {
            $resource = '{'.$resolver->getResourceParameter().'}';
            $relation = '{'.$resolver->getRelationParameter().'}';
            $id = '{'.$resolver->getIdParameter().'}';
            $subid = '{'.$resolver->getSubIdParameter().'}';

            $router->get("$resource", "$controller@index");
            $router->get("$resource/$id", "$controller@show");
            $router->post("$resource", "$controller@store");
            $router->put("$resource/$id", "$controller@update");
            $router->delete("$resource/$id", "$controller@destroy");

            $router->get("$resource/$id/$relation", "$controller@relatedIndex");
            $router->get("$resource/$id/$relation/$subid", "$controller@relatedIndex");
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->getConfigFile(), 'allay');

        parent::register();

        $this->registerLaravelResourceResolver();
    }

    /**
     * Register the resource resolver for Laravel, as singleton, to the IoC.
     *
     * @return void
     */
    protected function registerLaravelResourceResolver()
    {
        $this->app->singleton(LaravelResolver::class, function ($app) {
            return new LaravelResolver($app->make(Request::class));
        });
    }
}
