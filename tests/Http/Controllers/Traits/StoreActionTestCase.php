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
use ByCedric\Allay\Exceptions\ResourceMissingValidationException;
use ByCedric\Allay\Tests\Stubs\Http\Controllers\Traits\StoreAction;
use ByCedric\Allay\Tests\Stubs\Resource\Resource;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Mockery;

class StoreActionTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working instance of the show action trait.
     *
     * @return \ByCedric\Allay\Tests\Stubs\Http\Controllers\Traits\StoreAction
     */
    protected function getInstance()
    {
        return new StoreAction();
    }

    public function testResourcesWithoutValidatableThrowsException()
    {
        $action = $this->getInstance();
        $request = Mockery::mock(Request::class);
        $manager = Mockery::mock(Manager::class);
        $resolver = Mockery::mock(Resolver::class);
        $resource = Mockery::mock(Resource::class);
        $resourceName = 'awesome-resource';

        $resolver->shouldReceive('getResource')
            ->atLeast()->once()
            ->andReturn($resourceName);

        $manager->shouldReceive('make')
            ->once()
            ->with($resourceName)
            ->andReturn($resource);

        try {
            $action->store($request, $manager, $resolver);
        } catch (ResourceMissingValidationException $error) {
            $this->assertContains($resourceName, $error->getMessage(), 'Resource name not found in exception message.');
            return; // stop the test, good exception was thrown
        }

        $this->fail('Expected exception was not thrown when using invalidatable resource.');
    }

    public function testResourceIsMadeFromManagerUsingResolverAndIsFilledCreatedAndReturned()
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
            $action->store($request, $manager, $resolver),
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

        $resolver->shouldReceive('getResource')
            ->atLeast()->once()
            ->andReturn('resource');

        $manager->shouldReceive('make')
            ->once()
            ->with('resource')
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
            ->andReturn(true);

        try {
            $action->store($request, $manager, $resolver);
        } catch (ValidationException $error) {
            return; // stop the test, good exception was thrown
        }

        $this->fail('Expected exception was not thrown when using invalid resource.');
    }
}
