<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Tests\Resource;

use ByCedric\Allay\Exceptions\ResourceNotFoundException;
use ByCedric\Allay\Resource\Manager;
use ByCedric\Allay\Tests\Stubs\Resource\OtherResource;
use ByCedric\Allay\Tests\Stubs\Resource\Resource;
use Illuminate\Contracts\Container\Container;
use Mockery;

class ManagerTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working instance of the resource manager.
     *
     * @param  \Illuminate\Contracts\Container\Container $container (default: null)
     * @return \ByCedric\Allay\Resource\Manager
     */
    protected function getInstance(Container $container = null)
    {
        if (!$container) {
            $container = Mockery::mock(Container::class);
        }

        return new Manager($container);
    }

    public function testContainsDetectsRegisteredResources()
    {
        $manager = $this->getInstance();
        $manager->register('test', Resource::class);

        $this->assertTrue($manager->contains('test'), 'Registered resource was not detected by contains.');
        $this->assertFalse($manager->contains('nope'), 'Contains magically returned true without register.');
    }

    public function testMakeReturnsInstanceOfTheRegisteredResource()
    {
        $container = Mockery::mock(Container::class);
        $container->shouldReceive('make')
            ->once()
            ->with(Resource::class)
            ->andReturn(new Resource);

        $manager = $this->getInstance($container);
        $manager->register('test', Resource::class);

        $this->assertInstanceOf(
            Resource::class,
            $manager->make('test'),
            'Make did not return a valid instance.'
        );
    }

    public function testRegisterOverwritesExistingRegisteredResource()
    {
        $container = Mockery::mock(Container::class);
        $container->shouldReceive('make')
            ->once()
            ->with(OtherResource::class)
            ->andReturn(new OtherResource);

        $manager = $this->getInstance($container);
        $manager->register('test', Resource::class);
        $manager->register('test', OtherResource::class);

        $this->assertInstanceOf(
            OtherResource::class,
            $manager->make('test'),
            'Make did not return a valid instance.'
        );
    }

    public function testMakeThrowsExceptionWhenResourceWasNotFound()
    {
        $manager = $this->getInstance();

        try {
            $manager->make('test');
        } catch (\Exception $error) {
            return $this->assertInstanceOf(ResourceNotFoundException::class, $error, 'Manager threw wrong exception.');
        }

        $this->fail('Manager did not throw an exception.');
    }
}
