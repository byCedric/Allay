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

use ByCedric\Allay\Contracts\Exceptions\Handler;
use Illuminate\Http\Response;

abstract class ExceptionHandlerTestCase extends TestCase
{
    /**
     * Assert if the provided handler is capable in handling the provided exception.
     *
     * @param  \ByCedric\Allay\Contracts\Exceptions\Handler $handler
     * @param  \Exception                                   $error
     * @param  string                                       $message (default: '')
     * @return void
     */
    public function assertIsCapable(Handler $handler, \Exception $error, $message = '')
    {
        $this->assertTrue($handler->capable($error), $message);
    }

    /**
     * Assert if the provided handler is not capable in handling the provided exception.
     *
     * @param  \ByCedric\Allay\Contracts\Exceptions\Handler $handler
     * @param  \Exception                                   $error
     * @param  string                                       $message (default: '')
     * @return void
     */
    public function assertIsNotCapable(Handler $handler, \Exception $error, $message = '')
    {
        $this->assertFalse($handler->capable($error), $message);
    }

    /**
     * Assert if the handler returns a response for the exception, with the provided status code.
     *
     * @param  \ByCedric\Allay\Contracts\Exceptions\Handler $handler
     * @param  \Exception                                   $error
     * @param  int                                          $status
     * @param  string                                       $message (default: '')
     * @return void
     */
    public function assertHandlesToResponse(Handler $handler, \Exception $error, $status, $message = '')
    {
        $result = $handler->handle($error);

        $this->assertInstanceOf(Response::class, $result, $message);
        $this->assertSame($status, $result->getStatusCode(), $message);
    }

    abstract public function testCapableReturnsTrueWhenResourceExceptionWasProvided();

    abstract public function testCapableReturnsFalseWhenOtherExceptionsAreProvided();

    abstract public function testHandleReturnsResponseWithCorrectStatus();
}
