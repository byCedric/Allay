<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Tests\Stubs\Resource;

use ByCedric\Allay\Contracts\Resource\Relatable;
use Illuminate\Database\Eloquent\Relations\Relation;
use Mockery;

class RelatableResource extends Resource implements Relatable
{
    /**
     * {@inheritdoc}
     */
    public function getRelatableMethods()
    {
        return [
            'relation',
            'otherRelation',
            'notCallable',
        ];
    }

    public function relation()
    {
        return Mockery::mock(Relation::class);
    }

    public function otherRelation()
    {
        return Mockery::mock(Relation::class);
    }
}
