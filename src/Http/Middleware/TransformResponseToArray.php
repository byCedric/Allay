<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Http\Middleware;

use ByCedric\Allay\Contracts\Transformers\Transformer;
use Illuminate\Http\Response;

class TransformResponseToArray
{
    /**
     * The transformer to transform the content with.
     *
     * @var \ByCedric\Allay\Contracts\Transformers\Transformer
     */
    protected $transformer;

    /**
     * Create a new transform response middleware.
     *
     * @param  \ByCedric\Allay\Contracts\Transformers\Transformer $transformer
     * @return void
     */
    public function __construct(Transformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Make sure the content of the response is formatted using the transformer,
     * for the incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, \Closure $next)
    {
        $response = $next($request);

        if ($response instanceof Response) {
            $this->transformContent($response);
        }

        return $response;
    }

    /**
     * Replace the content of the response, with the transformed content.
     *
     * @param  \Illuminate\Http\Response $response
     * @return void
     */
    protected function transformContent(Response $response)
    {
        $response->setContent(
            $this->transformer->transform(
                $response->getOriginalContent(),
                $response->getStatusCode()
            )
        );
    }
}
