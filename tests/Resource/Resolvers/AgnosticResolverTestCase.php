<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Tests\Resource\Resolvers;

use ByCedric\Allay\Tests\Stubs\Resource\Resolvers\AgnosticResolver;

class AgnosticResolverTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working instance of the agnostic resolver.
     *
     * @return \ByCedric\Allay\Resource\Resolvers\AgnosticResolver
     */
    protected function getInstance()
    {
        return new AgnosticResolver();
    }

    public function testGetResourceParameterReturnsString()
    {
        $this->assertString(
            $this->getInstance()->getResourceParameter(),
            'Resolver did not return a valid resource parameter.'
        );
    }

    public function testGetResourceUsesParameterToFetchAndReturnRouteValue()
    {
        $this->assertSame(
            'resource',
            $this->getInstance()->getResource(),
            'Resolver did not return resource value correctly.'
        );
    }

    public function testGetIdParameterReturnsString()
    {
        $this->assertString(
            $this->getInstance()->getIdParameter(),
            'Resolver did not return a valid id parameter.'
        );
    }

    public function testGetIdUsesParameterToFetchAndReturnRouteValue()
    {
        $this->assertSame(
            'id',
            $this->getInstance()->getId(),
            'Resolver did not return id value correctly.'
        );
    }

    public function testGetRelationParameterReturnsString()
    {
        $this->assertString(
            $this->getInstance()->getRelationParameter(),
            'Resolver did not return a valid relation parameter.'
        );
    }

    public function testGetRelationUsesParameterToFetchAndReturnRouteValue()
    {
        $this->assertSame(
            'relation',
            $this->getInstance()->getRelation(),
            'Resolver did not return relation value correctly.'
        );
    }

    public function testGetSubIdParameterReturnsString()
    {
        $this->assertString(
            $this->getInstance()->getSubIdParameter(),
            'Resolver did not return a valid sub-id parameter.'
        );
    }

    public function testGetSubIdUsesParameterToFetchAndReturnRouteValue()
    {
        $this->assertSame('subid',
            $this->getInstance()->getSubId(),
            'Resolver did not return sub-id value correctly.'
        );
    }
}
