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
use ByCedric\Allay\Contracts\Resource\Validatable;
use ByCedric\Allay\Contracts\Resource\Writable;
use ByCedric\Allay\Exceptions\ResourceMissingValidationException;
use ByCedric\Allay\Tests\Stubs\Http\Controllers\Traits\UpdateAction;
use ByCedric\Allay\Tests\Stubs\Resource\Resource;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Mockery;

class UpdateActionTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working instance of the show action trait.
     *
     * @return \ByCedric\Allay\Tests\Stubs\Http\Controllers\Traits\UpdateAction
     */
    protected function getInstance()
    {
        return new UpdateAction();
    }

    public function testResourcesWithoutValidatableThrowsException()
    {
        $action = $this->getInstance();
        $request = Mockery::mock(Request::class);
        $manager = Mockery::mock(Manager::class);
        $resolver = Mockery::mock(Resolver::class);
        $resource = Mockery::mock(Resource::class);

        $resolver->shouldReceive('getResource')
            ->atLeast()->once()
            ->andReturn('resource');

        $manager->shouldReceive('make')
            ->once()
            ->with('resource')
            ->andReturn($resource);

        try {
            $action->update($request, $manager, $resolver);
        } catch (ResourceMissingValidationException $error) {
            return; // stop the test, good exception was thrown
        }

        $this->fail('Expected exception was not thrown when using invalidatable resource.');
    }

    public function testResourceIsMadeFromManagerUsingResolverAndIsFilledSavedAndReturned()
    {
        $action = $this->getInstance();
        $request = Mockery::mock(Request::class);
        $manager = Mockery::mock(Manager::class);
        $resolver = Mockery::mock(Resolver::class);
        $resource = Mockery::mock(Validatable::class);
        $validator = Mockery::mock(Validator::class);

        $resolver->shouldReceive('getResource')
            ->atLeast()->once()
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

        $request->shouldReceive('all')
            ->once()
            ->andReturn(['key' => 'value']);

        $resource->shouldReceive('fill')
            ->once()
            ->with(Mockery::type('array'))
            ->andReturn($resource);

        $resource->shouldReceive('getValidatorInstance')
            ->once()
            ->andReturn($validator);

        $validator->shouldReceive('fails')
            ->once()
            ->andReturn(false);

        $resource->shouldReceive('save')
            ->once();

        $this->assertSame(
            $resource,
            $action->update($request, $manager, $resolver),
            'Action is not returning response correctly.'
        );
    }

    public function testInvalidResourcesThrowsException()
    {
        $action = $this->getInstance();
        $request = Mockery::mock(Request::class);
        $manager = Mockery::mock(Manager::class);
        $resolver = Mockery::mock(Resolver::class);
        $resource = Mockery::mock(Validatable::class);
        $validator = Mockery::mock(Validator::class);

        $resolver->shouldReceive('getResource')->andReturn('irrelevant');
        $manager->shouldReceive('make')->andReturn($resource);
        $resolver->shouldReceive('getId')->andReturn('irrelevant');
        $resource->shouldReceive('findOrFail')->andReturn($resource);
        $request->shouldReceive('all')->andReturn('irrelevant');
        $resource->shouldReceive('fill')->andReturn($resource);

        $resource->shouldReceive('getValidatorInstance')
            ->once()
            ->andReturn($validator);

        $validator->shouldReceive('fails')
            ->once()
            ->andReturn(true);

        try {
            $action->update($request, $manager, $resolver);
        } catch (ValidationException $error) {
            return; // stop the test, good exception was thrown
        }

        $this->fail('Expected exception was not thrown when using invalid resource.');
    }

    public function testWritableInterfaceIsUsedWhenImplemented()
    {
        $action = $this->getInstance();
        $request = Mockery::mock(Request::class);
        $manager = Mockery::mock(Manager::class);
        $resolver = Mockery::mock(Resolver::class);
        $resource = Mockery::mock(Validatable::class, Writable::class);
        $builder = Mockery::mock(Builder::class);
        $validator = Mockery::mock(Validator::class);

        $resolver->shouldReceive('getResource')->andReturn('irrelevant');
        $manager->shouldReceive('make')->andReturn($resource);

        $resource->shouldReceive('newQuery')
            ->once()
            ->andReturn($builder);

        $resource->shouldReceive('getWritableQuery')
            ->with($builder)
            ->once()
            ->andReturn($builder);

        $resolver->shouldReceive('getId')->andReturn('irrelevant');
        $builder->shouldReceive('findOrFail')->andReturn($resource);
        $request->shouldReceive('all')->andReturn('irrelevant');
        $resource->shouldReceive('fill')->andReturn($resource);
        $resource->shouldReceive('getValidatorInstance')->andReturn($validator);
        $validator->shouldReceive('fails')->andReturn(false);
        $resource->shouldReceive('save');

        $action->update($request, $manager, $resolver);
    }
}
