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

use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Response;

class UpdateStatusCodeByRequestMethod
{
    /**
     * The status code mapping, by request method.
     *
     * @var array
     */
    protected $statuses;

    /**
     * Create a new response status updating middleware.
     *
     * @param  \Illuminate\Contracts\Config\Repository $config
     * @return void
     */
    public function __construct(Repository $config)
    {
        $this->statuses = $config->get('allay.statuses');
    }

    /**
     * Update the status code of the response, if it's set to default and
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, \Closure $next)
    {
        $response = $next($request);

        if ($response instanceof Response) {
            $this->updateStatus($request, $response);
        }

        return $response;
    }

    /**
     * Update the status code of the response, by request method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response $response
     * @return void
     */
    protected function updateStatus($request, Response $response)
    {
        if ($this->shouldUpdateStatus($request, $response)) {
            $response->setStatusCode($this->statuses[$request->getMethod()]);
        }
    }

    /**
     * Determine if we should update the status code, by request method.
     *
     * @param  \Illuminate\Http\Request $request
     * @return boolean
     */
    protected function shouldUpdateStatus($request, Response $response)
    {
        return $response->getStatusCode() === 200 && isset($this->statuses[$request->getMethod()]);
    }
}
