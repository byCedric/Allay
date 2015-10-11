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

use ByCedric\Allay\Resource\Resolvers\LumenResolver;
use Illuminate\Http\Request;

class LumenServiceProvider extends AgnosticServiceProvider
{
    /**
     * Get the package's configuration file.
     *
     * @return string
     */
    protected function getConfigFile()
    {
        return __DIR__.'/../Config/LumenConfig.php';
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

        $this->registerLumenResourceResolver();
    }

    /**
     * Register the resource resolver for Lumen, as singleton, to the IoC.
     *
     * @return void
     */
    protected function registerLumenResourceResolver()
    {
        $this->app->singleton(LumenResolver::class, function ($app) {
            return new LumenResolver($app->make(Request::class));
        });
    }
}
