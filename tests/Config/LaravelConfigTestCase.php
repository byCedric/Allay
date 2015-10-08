<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Tests\Config;

use ByCedric\Allay\Contracts\Exceptions\Handler as ExceptionHandlerContract;
use ByCedric\Allay\Contracts\Exceptions\Manager as ExceptionManagerContract;
use ByCedric\Allay\Contracts\Resource\Manager as ResourceManagerContract;
use ByCedric\Allay\Contracts\Transformers\Transformer as TransformerContract;

class LaravelConfigTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get the contents of the configuration file.
     *
     * @param  string $item (default: null)
     * @return array
     */
    protected function getConfig($item = null)
    {
        $config = include __DIR__ . '/../../src/Config/LaravelConfig.php';

        if (!empty($item)) {
            foreach (explode('.', $item) as $subitem) {
                $config = $config[$subitem];
            }
        }

        return $config;
    }

    public function testConfigReturnsAnArray()
    {
        $this->assertArray($this->getConfig(), 'Configuration did not return array.');
    }

    public function testResourceManagerIsDefinedAndImplementsContract()
    {
        $this->assertSubclassOf(
            ResourceManagerContract::class,
            $this->getConfig('resources.manager'),
            'Defined resource manager is not compatible.'
        );
    }

    public function testResourceModelsIsAnEmptyArray()
    {
        $models = $this->getConfig('resources.models');

        $this->assertArray($models, 'Defined resource models is not an array.');
        $this->assertEmpty($models, 'Defined resource models is not empty.');
    }

    public function testRouteControllerIsDefined()
    {
        $this->assertClassExists($this->getConfig('routes.controller'), 'Defined controller class doesn\'t exists.');
    }

    public function testRouteSettingsIsAnArray()
    {
        $this->assertArray($this->getConfig('routes.settings'), 'Routes settings must be an array.');
    }

    public function testRouteSettingsMiddlewareExists()
    {
        $middleware = $this->getConfig('routes.settings.middleware');

        if (!empty($middleware)) {
            foreach ($middleware as $classType) {
                $this->assertClassExists($classType, "Defined middleware \"$classType\" doesn't exists.");
            }
        }
    }

    public function testTransformerIsDefinedAndImplementsContract()
    {
        $this->assertSubclassOf(
            TransformerContract::class,
            $this->getConfig('transformer'),
            'Defined transformer is not copatible.'
        );
    }

    public function testExceptionManagerIsDefinedAndImplementsContract()
    {
        $this->assertSubclassOf(
            ExceptionManagerContract::class,
            $this->getConfig('exceptions.manager'),
            'Defined exeption manager is not compatible.'
        );
    }

    public function testExceptionHandlersImplementsContract()
    {
        $handlers = $this->getConfig('exceptions.handlers');

        if (!empty($handlers)) {
            foreach ($handlers as $handler) {
                $this->assertSubclassOf(
                    ExceptionHandlerContract::class,
                    $handler,
                    "Defined exception handler \"$handler\" is not compatible."
                );
            }
        }
    }

    public function testStatusesIsAnArray()
    {
        $this->assertArray($this->getConfig('statuses'), 'Statuses must be an array.');
    }
}
