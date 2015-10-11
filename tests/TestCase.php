<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Tests;

use Mockery;
use PHPUnit_Framework_TestCase;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * When a test ends, make sure we remove all mocks.
     *
     * @return void
     */
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * Assert if the provided value is a string.
     *
     * @param  mixed  $value
     * @param  string $message (default: '')
     * @return void
     */
    public function assertString($value, $message = '')
    {
        $this->assertInternalType('string', $value, $message);
    }

    /**
     * Assert if the provided value is an array.
     *
     * @param  mixed  $value
     * @param  string $message (default: '')
     * @return void
     */
    public function assertArray($value, $message = '')
    {
        $this->assertInternalType('array', $value, $message);
    }

    /**
     * Assert if the provided string is a subclass of the expected subclass.
     *
     * @param  string $expected
     * @param  string $classType
     * @param  string $message   (default: '')
     * @return void
     */
    public function assertSubclassOf($expected, $classType, $message = '')
    {
        $this->assertTrue(is_subclass_of($classType, $expected), $message);
    }

    /**
     * Assert if the provided class type exists.
     *
     * @param  string $classType
     * @param  string $message   (default: '')
     * @return void
     */
    public function assertClassExists($classType, $message = '')
    {
        $this->assertTrue(class_exists($classType), $message);
    }

    /**
     * Call protected method that can't be called otherwise.
     *
     * @param  object &$instance
     * @param  string $method
     * @param  array  $params
     * @return mixed
     */
    public function callProtectedMethod(&$instance, $method, array $params = [])
    {
        $reflection = new \ReflectionMethod(get_class($instance), $method);
        $reflection->setAccessible(true);

        if (!empty($params)) {
            return $reflection->invokeArgs($instance, $params);
        }

        return $reflection->invoke($instance);
    }
}
