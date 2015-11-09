<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Exceptions;

use Illuminate\Http\Response;

class ResourceRelationNotFoundException extends ResourceException
{
    /**
     * The requested relation that was not found.
     *
     * @var string
     */
    protected $relation;

    /**
     * Create a new resource reltion not found exception.
     *
     * @param  string     $resource
     * @param  string     $relation
     * @param  \Exception $previous (default: null)
     * @return void
     */
    public function __construct($resource, $relation, \Exception $previous = null)
    {
        parent::__construct(
            $resource,
            "The (requested) relation \"$relation\" for resource \"$resource\" was not found.",
            Response::HTTP_NOT_FOUND,
            $previous
        );

        $this->relation = $relation;
    }

    /**
     * Get the requested relation name.
     *
     * @return string
     */
    public function getRelation()
    {
        return $this->relation;
    }
}
