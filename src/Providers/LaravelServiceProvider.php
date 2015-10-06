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

use Illuminate\Contracts\Config\Repository;
use Illuminate\Routing\Router;

class LaravelServiceProvider extends AgnosticServiceProvider
{
    /**
     * The configuration path to use.
     *
     * @var string
     */
    const CONFIG_FILE = __DIR__ . '/../config.php';

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
        $this->publishes([
            self::CONFIG_FILE => config_path('allay.php'),
        ]);

        $this->populateExceptionManager();
        $this->populateResourceManager();
        $this->initializeResourceRoutes();
    }

    /**
     * Boot all resource actions and their routes.
     *
     * @return void
     */
    protected function initializeResourceRoutes()
    {
        $router = $this->app->make(Router::class);

        $controller = $this->getConfig('allay.routes.controller');
        $settings = $this->getConfig('allay.routes.settings');

        $router->group($settings, function ($router) use ($controller) {
            $router->get('{resource}', "$controller@index");
            $router->get('{resource}/{id}', "$controller@show");
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(self::CONFIG_FILE, 'allay');

        $this->registerExceptionManager();
        $this->registerResourceManager();
        $this->bindExceptionManager();
        $this->bindResourceManager();
        $this->bindTransformer();
    }
}
