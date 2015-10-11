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
use ByCedric\Allay\Contracts\Resource\Readable;
use ByCedric\Allay\Contracts\Resource\Resolver;
use ByCedric\Allay\Tests\Stubs\Http\Controllers\Traits\ShowAction;
use ByCedric\Allay\Tests\Stubs\Resource\Resource;
use Illuminate\Database\Query\Builder;
use Mockery;

class ShowActionTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working instance of the show action trait.
     *
     * @return \ByCedric\Allay\Tests\Stubs\Http\Controllers\Traits\ShowAction
     */
    protected function getInstance()
    {
        return new ShowAction();
    }

    public function testResourceIsMadeFromManagerUsingResolverAndReturnedById()
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
            ->andReturn('result');

        $this->assertSame('result', $action->show($manager, $resolver), 'Action is not returning response correctly.');
    }

    public function testReadableInterfaceIsUsedWhenImplemented()
    {
        $action = $this->getInstance();
        $manager = Mockery::mock(Manager::class);
        $resolver = Mockery::mock(Resolver::class);
        $resource = Mockery::mock(Readable::class);
        $builder = Mockery::mock(Builder::class);

        $resolver->shouldReceive('getResource')->andReturn('irrelevant');
        $manager->shouldReceive('make')->andReturn($resource);
        $resolver->shouldReceive('getId')->andReturn('irrelevant');

        $resource->shouldReceive('getReadableQuery')
            ->once()
            ->andReturn($builder);

        $builder->shouldReceive('findOrFail')
            ->once();

        $action->show($manager, $resolver);
    }
}
