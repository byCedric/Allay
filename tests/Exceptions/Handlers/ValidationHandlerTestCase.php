<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Tests\Exceptions\Handlers;

use ByCedric\Allay\Exceptions\Handlers\ValidationHandler;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Contracts\Support\MessageProvider;
use Illuminate\Contracts\Validation\ValidationException;
use Mockery;

class ValidationHandlerTestCase extends \ByCedric\Allay\Tests\ExceptionHandlerTestCase
{
    /**
     * Get a working instance of the resource not found handler.
     *
     * @return \ByCedric\Allay\Exceptions\Handlers\ValidationHandler
     */
    protected function getInstance()
    {
        return new ValidationHandler();
    }

    public function testCapableReturnsTrueWhenResourceExceptionWasProvided()
    {
        $messages = Mockery::mock(MessageProvider::class);

        $this->assertIsCapable(
            $this->getInstance(),
            new ValidationException($messages),
            'Handler was not capable of handling designated exception.'
        );
    }

    public function testCapableReturnsFalseWhenOtherExceptionsAreProvided()
    {
        $this->assertIsNotCapable(
            $this->getInstance(),
            new \InvalidArgumentException(),
            'Handler was capable of "strange" exception.'
        );
    }

    public function testHandleReturnsResponseWithCorrectStatus()
    {
        $errors = Mockery::mock(MessageBag::class);
        $messages = Mockery::mock(MessageProvider::class);

        $errors->shouldReceive('all')
            ->once()
            ->andReturn(['error']);

        $messages->shouldReceive('getMessageBag')
            ->once()
            ->andReturn($errors);

        $this->assertHandlesToResponse(
            $this->getInstance(),
            new ValidationException($messages),
            422,
            'Handler returned a malformed response.'
        );
    }
}
