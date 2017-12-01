<?php

namespace ByCedric\Allay\Exceptions\Handlers;

use Exception;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HttpExceptionHandler implements \ByCedric\Allay\Contracts\Exceptions\Handler
{
    /**
     * {@inheritdoc}
     */
    public function capable(Exception $error)
    {
        return $error instanceof HttpException;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Exception $error)
    {
        return new Response(
            $this->getErrorArray($error),
            $error->getStatusCode(),
            $error->getHeaders()
        );
    }

    /**
     * Get an error array from the provided http exception.
     *
     * @param  \Symfony\Component\HttpKernel\Exception\HttpException $error
     * @return array
     */
    protected function getErrorArray(HttpException $error)
    {
        if ($error->getMessage()) {
            return [
                'detail' => $error->getMessage(),
                'code' => $error->getCode() ?: $error->getStatusCode(),
                'exception' => class_basename($error),
            ];
        }

        return ['title' => ucfirst(snake_case(class_basename($error), ' '))];
    }
}
