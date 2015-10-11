<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Tests\Config;

class LaravelConfigTestCase extends \ByCedric\Allay\Tests\ConfigTestCase
{
    /**
     * Get the path of the configuration file, to test.
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return __DIR__.'/../../src/Config/LaravelConfig.php';
    }
}
