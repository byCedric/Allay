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

interface Readable
{
    /**
     * Get an illuminate query builder, scoped on all readable resources.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function getReadableQuery();
}
