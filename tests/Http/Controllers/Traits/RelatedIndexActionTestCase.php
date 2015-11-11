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
use ByCedric\Allay\Contracts\Resource\Relatable;
use ByCedric\Allay\Contracts\Resource\Resolver;
use ByCedric\Allay\Exceptions\ResourceMissingRelatableException;
use ByCedric\Allay\Exceptions\ResourceRelationNotFoundException;
use ByCedric\Allay\Tests\Stubs\Http\Controllers\Traits\RelatedIndexAction;
use ByCedric\Allay\Tests\Stubs\Resource\RelatableResource;
use ByCedric\Allay\Tests\Stubs\Resource\Resource;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Mockery;

class RelatedIndexActionTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working instance of the related index action trait.
     *
     * @return \ByCedric\Allay\Tests\Stubs\Http\Controllers\Traits\RelatedIndexAction
     */
    protected function getInstance()
    {
        return new RelatedIndexAction();
    }

    public function testResourcesWithoutRelatableThrowsException()
    {
        $action = $this->getInstance();
        $manager = Mockery::mock(Manager::class);
        $resolver = Mockery::mock(Resolver::class);
        $resource = Mockery::mock(Resource::class);

        $resolver->shouldReceive('getResource')
            ->atLeast()->once()
            ->andReturn('resource');

        $manager->shouldReceive('make')
            ->atLeast()->once()
            ->with('resource')
            ->andReturn($resource);

        try {
            $action->relatedIndex($manager, $resolver);
        } catch (ResourceMissingRelatableException $error) {
            return; // stop the test, good exception was thrown
        }

        $this->fail('Expected exception was not thrown when using non-relatable resource.');
    }

    public function testUnlistedRelationThrowsException()
    {
        $action = $this->getInstance();
        $manager = Mockery::mock(Manager::class);
        $resolver = Mockery::mock(Resolver::class);
        $resource = Mockery::mock(Resource::class, Relatable::class);

        $resolver->shouldReceive('getResource')
            ->atLeast()->once()
            ->andReturn('resource');

        $manager->shouldReceive('make')
            ->atLeast()->once()
            ->with('resource')
            ->andReturn($resource);

        $resolver->shouldReceive('getRelationMethod')
            ->atLeast()->once()
            ->andReturn('relation');

        $resolver->shouldReceive('getRelation')
            ->atLeast()->once()
            ->andReturn('relation');

        $resource->shouldReceive('getRelatableMethods')
            ->atLeast()->once()
            ->andReturn(['otherRelation']);

        try {
            $action->relatedIndex($manager, $resolver);
        } catch (ResourceRelationNotFoundException $error) {
            return; // stop the test, good exception was thrown
        }

        $this->fail('Expected exception was not thrown when using unlisted relations.');
    }

    public function testNonCallableRelationThrownsException()
    {
        $action = $this->getInstance();
        $manager = Mockery::mock(Manager::class);
        $resolver = Mockery::mock(Resolver::class);
        $resource = new RelatableResource();

        $resolver->shouldReceive('getResource')
            ->atLeast()->once()
            ->andReturn('resource');

        $manager->shouldReceive('make')
            ->atLeast()->once()
            ->with('resource')
            ->andReturn($resource);

        $resolver->shouldReceive('getRelationMethod')
            ->atLeast()->once()
            ->andReturn('notCallable');

        $resolver->shouldReceive('getRelation')
            ->atLeast()->once()
            ->andReturn('notCallable');

        try {
            $action->relatedIndex($manager, $resolver);
        } catch (ResourceRelationNotFoundException $error) {
            return; // stop the test, good exception was thrown
        }

        $this->fail('Expected exception was not thrown when using non-callable relations.');
    }

    public function testRelatedResourceThatImplementsReadableIsCalled()
    {
        $action = $this->getInstance();
        $manager = Mockery::mock(Manager::class);
        $resolver = Mockery::mock(Resolver::class);
        $resource = Mockery::mock(Resource::class, Relatable::class, Readable::class);
        $relatedResource = Mockery::mock(Resource::class, Readable::class);
        $relation = Mockery::mock(Relation::class);
        $builder = Mockery::mock(Builder::class);

        $resolver->shouldReceive('getResource')
            ->atLeast()->once()
            ->andReturn('resource');

        $manager->shouldReceive('make')
            ->atLeast()->once()
            ->with('resource')
            ->andReturn($resource);

        $resolver->shouldReceive('getRelationMethod')
            ->atLeast()->once()
            ->andReturn('relation');

        $resolver->shouldReceive('getId')
            ->atLeast()->once()
            ->andReturn('1337');

        $resource->shouldReceive('newQuery')
            ->atLeast()->once()
            ->andReturn($resource);

        $resource->shouldReceive('getReadableQuery')
            ->once()
            ->andReturn($resource);

        $resource->shouldReceive('findOrFail')
            ->with('1337')
            ->once()
            ->andReturn($resource);

        $resource->shouldReceive('relation')
            ->atLeast()->once()
            ->andReturn($relation);

        $resource->shouldReceive('getRelatableMethods')
            ->atLeast()->once()
            ->andReturn(['relation']);

        $relation->shouldReceive('getRelated')
            ->atLeast()->once()
            ->andReturn($relatedResource);

        $relation->shouldReceive('getQuery')
            ->atLeast()->once()
            ->andReturn($builder);

        $relatedResource->shouldReceive('getReadableQuery')
            ->with($builder)
            ->once()
            ->andReturn($builder);

        $builder->shouldReceive('paginate')
            ->once();

        $action->relatedIndex($manager, $resolver);
    }

    public function testRelatedResourceWithoutReadableIsUsable()
    {
        $action = $this->getInstance();
        $manager = Mockery::mock(Manager::class);
        $resolver = Mockery::mock(Resolver::class);
        $resource = Mockery::mock(Resource::class, Relatable::class);
        $relatedResource = Mockery::mock(Resource::class);
        $relation = Mockery::mock(Relation::class);

        $resolver->shouldReceive('getResource')
            ->atLeast()->once()
            ->andReturn('resource');

        $manager->shouldReceive('make')
            ->atLeast()->once()
            ->with('resource')
            ->andReturn($resource);

        $resolver->shouldReceive('getRelationMethod')
            ->atLeast()->once()
            ->andReturn('relation');

        $resolver->shouldReceive('getId')
            ->atLeast()->once()
            ->andReturn('1337');

        $resource->shouldReceive('findOrFail')
            ->with('1337')
            ->once()
            ->andReturn($resource);

        $resource->shouldReceive('relation')
            ->atLeast()->once()
            ->andReturn($relation);

        $resource->shouldReceive('getRelatableMethods')
            ->atLeast()->once()
            ->andReturn(['relation']);

        $relation->shouldReceive('getRelated')
            ->atLeast()->once()
            ->andReturn($relatedResource);

        $relation->shouldReceive('paginate')
            ->once();

        $action->relatedIndex($manager, $resolver);
    }
}
