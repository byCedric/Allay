<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Tests\Http\Controllers\Traits;

use ByCedric\Allay\Contracts\Resource\Manager;
use ByCedric\Allay\Contracts\Resource\Resolver;
use ByCedric\Allay\Contracts\Resource\Writable;
use ByCedric\Allay\Tests\Stubs\Http\Controllers\Traits\DestroyAction;
use ByCedric\Allay\Tests\Stubs\Resource\Resource;
use Illuminate\Database\Query\Builder;
use Mockery;

class DestroyActionTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working instance of the show action trait.
     *
     * @return \ByCedric\Allay\Tests\Stubs\Http\Controllers\Traits\DestroyAction
     */
    protected function getInstance()
    {
        return new DestroyAction();
    }

    public function testResourceIsMadeFromManagerUsingResolverIsDeletedByIdAndReturned()
    {
        $action = $this->getInstance();
        $manager = Mockery::mock(Manager::class);
        $resolver = Mockery::mock(Resolver::class);
        $resource = Mockery::mock(Resource::class);

        $resolver->shouldReceive('getResource')
            ->once()
            ->andReturn('resource');

        $manager->shouldReceive('make')
            ->once()
            ->with('resource')
            ->andReturn($resource);

        $resolver->shouldReceive('getId')
            ->once()
            ->andReturn('7');

        $resource->shouldReceive('findOrFail')
            ->once()
            ->with('7')
            ->andReturn($resource);

        $resource->shouldReceive('delete')
            ->once();

        $this->assertSame(
            $resource,
            $action->destroy($manager, $resolver),
            'Action is not returning response correctly.'
        );
    }

    public function testWritableInterfaceIsUsedWhenImplemented()
    {
        $action = $this->getInstance();
        $manager = Mockery::mock(Manager::class);
        $resolver = Mockery::mock(Resolver::class);
        $resource = Mockery::mock(Writable::class);
        $builder = Mockery::mock(Builder::class);

        $resolver->shouldReceive('getResource')->andReturn('irrelevant');
        $manager->shouldReceive('make')->andReturn($resource);
        $resolver->shouldReceive('getId')->andReturn('irrelevant');

        $resource->shouldReceive('newQuery')
            ->once()
            ->andReturn($builder);

        $resource->shouldReceive('getWritableQuery')
            ->with($builder)
            ->once()
            ->andReturn($builder);

        $builder->shouldReceive('findOrFail')
            ->once()
            ->andReturn($builder);

        $builder->shouldReceive('delete')
            ->once();

        $action->destroy($manager, $resolver);
    }
}
