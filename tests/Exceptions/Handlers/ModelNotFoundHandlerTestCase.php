<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Tests\Exceptions\Handlers;

use ByCedric\Allay\Contracts\Resource\Manager as ResourceManager;
use ByCedric\Allay\Exceptions\Handlers\ModelNotFoundHandler;
use ByCedric\Allay\Tests\Stubs\Resource\Resource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery;

class ModelNotFoundHandlerTestCase extends \ByCedric\Allay\Tests\ExceptionHandlerTestCase
{
    /**
     * Get a working instance of the model not found handler.
     *
     * @return \ByCedric\Allay\Exceptions\Handlers\ModelNotFoundHandler
     */
    protected function getInstance(ResourceManager $resources = null)
    {
        if (!$resources) {
            $resources = Mockery::mock(ResourceManager::class);
        }

        return new ModelNotFoundHandler($resources);
    }

    public function testCapableReturnsTrueWhenResourceExceptionWasProvided()
    {
        $this->assertIsCapable(
            $this->getInstance(),
            new ModelNotFoundException(),
            'Handler was not capable of handling designated exception.'
        );
    }

    public function testCapableReturnsFalseWhenOtherExceptionsAreProvided()
    {
        $this->assertIsNotCapable(
            $this->getInstance(),
            new \InvalidArgumentException(),
            'Handler was capable of "strange" exception.'
        );
    }

    public function testHandleReturnsResponseWithCorrectStatus()
    {
        $resources = Mockery::mock(ResourceManager::class);
        $resources->shouldReceive('name')
            ->atLeast()->once()
            ->andReturn('awesome-resource');

        $this->assertHandlesToResponse(
            $this->getInstance($resources),
            new ModelNotFoundException(),
            404,
            'Handler returned a malformed response.'
        );
    }
}
