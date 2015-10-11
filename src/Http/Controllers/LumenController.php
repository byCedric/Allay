<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Http\Controllers;

class LumenController extends \Laravel\Lumen\Routing\Controller
{
    use Traits\IndexAction,
        Traits\ShowAction,
        Traits\StoreAction,
        Traits\UpdateAction,
        Traits\DestroyAction;
}
