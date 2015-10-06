<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Transformers;

class ArrayTransformer implements \ByCedric\Allay\Contracts\Transformers\Transformer
{
    /**
     * Transform the given value into a plain array.
     *
     * @param  mixed   $value
     * @param  integer $status
     * @return array
     */
    public function transform($value, $status)
    {
        if ($value instanceof \Illuminate\Contracts\Support\Arrayable) {
            $value = $value->toArray();
        }

        return (array) $value;
    }
}
