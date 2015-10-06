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

    public function testBooleanIsTransformedToArray()
    {
        $this->assertArray(
            $this->getInstance()->transform(true, 200),
            'Boolean is not transformed to array.'
        );
    }

    public function testNumberIsTransformedToArray()
    {
        $this->assertArray(
            $this->getInstance()->transform(123, 200),
            'Number is not transformed to array.'
        );
    }

    public function testStringIsTransformedToArray()
    {
        $this->assertArray(
            $this->getInstance()->transform('test string', 200),
            'String is not transformed to array.'
        );
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
