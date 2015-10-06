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

use ByCedric\Allay\Contracts\Resource\Manager as ResourceManager;
use Illuminate\Http\Request;

class LaravelController extends \Illuminate\Routing\Controller
{
    use Traits\IndexActionTrait,
        Traits\ShowActionTrait;



    // index
    // show
    // store
    // update
    // destroy
    //
    // relatedIndex
    // relatedShow
    // relatedStore
    // relatedUpdate
    // relatedDestroy
}
