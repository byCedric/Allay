<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Tests\Transformers;

use ByCedric\Allay\Transformers\ArrayTransformer;
use Illuminate\Contracts\Support\Arrayable;
use Mockery;

class ArrayTransformerTestCase extends \ByCedric\Allay\Tests\TestCase
{
    /**
     * Get a working instance of the transformer.
     *
     * @return \ByCedric\Allay\Transformers\ArrayTransformer
     */
    protected function getInstance()
    {
        return new ArrayTransformer();
    }

    public function testNonArrayIsNotTransformedToArray()
    {
        $scalars = [
            'string' => 'My string',
            'bool' => true,
            'float' => 1.2,
            'int' => 13,
            'null'=> null,
        ];

        foreach ($scalars as $type => $value) {
            $this->assertInternalType(
                $type,
                $this->getInstance()->transform($value, 200),
                "Scalar type {$type} was transformed."
            );
        }
    }

    public function testArrayIsTransformedToArray()
    {
        $this->assertArray(
            $this->getInstance()->transform(['test' => 'array'], 200),
            'Array is not transformed to array.'
        );
    }

    public function testArrayableIsTransformedToArray()
    {
        $arrayable = Mockery::mock(Arrayable::class);
        $arrayable->shouldReceive('toArray')
            ->once()
            ->andReturn(['arrayable' => 'response']);

        $this->assertArray(
            $this->getInstance()->transform($arrayable, 200),
            'Arrayable is not transformed to array.'
        );
    }
}
