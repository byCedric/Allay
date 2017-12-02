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
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Validation\ValidationException;
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
        $validator = Mockery::mock(Validator::class);

        $this->assertIsCapable(
            $this->getInstance(),
            new ValidationException($validator),
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
        $validator = Mockery::mock(Validator::class);
        $errors = Mockery::mock(MessageBag::class);

        $validator->shouldReceive('errors')
            ->once()
            ->andReturn($errors);

        $errors->shouldReceive('messages')
            ->once()
            ->andReturn(['error']);

        $this->assertHandlesToResponse(
            $this->getInstance(),
            new ValidationException($validator),
            422,
            'Handler returned a malformed response.'
        );
    }
}
