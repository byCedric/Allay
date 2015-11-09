<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Tests\Exceptions;

use ByCedric\Allay\Exceptions\ResourceRelationNotFoundException;

class ResourceRelationNotFoundExceptionTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working instance of the resource not found exception.
     *
     * @param  string                                                       $name     (default: 'test')
     * @param  string                                                       $relation (default: 'relation')
     * @return \ByCedric\Allay\Exceptions\ResourceRelationNotFoundException
     */
    protected function getInstance($name = 'test', $relation = 'relation')
    {
        return new ResourceRelationNotFoundException($name, $relation);
    }

    public function testResourceNotFoundExceptionReturnsMessage()
    {
        $this->assertString(
            $this->getInstance()->getMessage(),
            'Exception did not return a valid message.'
        );
    }

    public function testResourceNotFoundExceptionReturnsStatusCode()
    {
        $this->assertSame(
            404,
            $this->getInstance()->getCode(),
            'Exception did not return a valid status code.'
        );
    }

    public function testGetRelationReturnsRequestedRelationName()
    {
        $this->assertSame(
            'relation',
            $this->getInstance('test', 'relation')->getRelation(),
            'Exception did not return the correct relation name.'
        );
    }
}
