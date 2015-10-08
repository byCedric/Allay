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

class LaravelController extends \Illuminate\Routing\Controller
{
    use Traits\IndexActionTrait,
        Traits\ShowActionTrait;
}
