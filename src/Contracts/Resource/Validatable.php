<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Contracts\Resource;

interface Validatable
{
    /**
     * Get an illuminate validator instance, set with all rules and attributes.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance();
}
