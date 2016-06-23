<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Exceptions\Handlers;

use ByCedric\Allay\Contracts\Resource\Manager as ResourceManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;

class ModelNotFoundHandler implements \ByCedric\Allay\Contracts\Exceptions\Handler
{
    /**
     * The resource manager to fetch the model name from.
     *
     * @var \ByCedric\Allay\Contracts\Resource\Manager
     */
    private $resources;

    /**
     * Create a new model not found handler instance.
     *
     * @param \ByCedric\Allay\Contracts\Resource\Manager $resources
     */
    public function __construct(ResourceManager $resources)
    {
        $this->resources = $resources;
    }

    /**
     * Determine if the handler is capable of handling the given exception.
     *
     * @param  \Exception $error
     * @return bool
     */
    public function capable(\Exception $error)
    {
        return $error instanceof ModelNotFoundException;
    }

    /**
     * Handle the given exception to return a valid response.
     *
     * @param  \Exception                $error
     * @return \Illuminate\Http\Response
     */
    public function handle(\Exception $error)
    {
        return new Response([
            'detail' => "The resource \"{$this->getModelName($error)}\", with the requested id, was not found.",
        ], Response::HTTP_NOT_FOUND);
    }

    /**
     * Get the model name, without the namespace.
     *
     * @param  \Illuminate\Database\Eloquent\ModelNotFoundException $error
     * @return string
     */
    protected function getModelName(ModelNotFoundException $error)
    {
        return $this->resources->name($error->getModel());
    }
}
