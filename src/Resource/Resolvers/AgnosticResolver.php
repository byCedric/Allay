<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Resource\Resolvers;

abstract class AgnosticResolver implements \ByCedric\Allay\Contracts\Resource\Resolver
{
    /**
     * Get the value of the given route parameter.
     *
     * @param  string $key
     * @return string
     */
    abstract protected function getRouteParameter($key);

    /**
     * Get a camel case string of the provided value.
     *
     * @param  string $value
     * @return string
     */
    protected function toCamelCase($value)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $value))));
    }

    /**
     * Get the requested resource name.
     *
     * @return string
     */
    public function getResource()
    {
        return $this->getRouteParameter($this->getResourceParameter());
    }

    /**
     * Get the name of the resource route parameter.
     *
     * @return string
     */
    public function getResourceParameter()
    {
        return 'resource';
    }

    /**
     * Get the requested resource identifier.
     *
     * @return string
     */
    public function getId()
    {
        return $this->getRouteParameter($this->getIdParameter());
    }

    /**
     * Get the name of the id route parameter.
     *
     * @return string
     */
    public function getIdParameter()
    {
        return 'id';
    }

    /**
     * Get the relation of the requested resource.
     *
     * @return string
     */
    public function getRelation()
    {
        return $this->getRouteParameter($this->getRelationParameter());
    }

    /**
     * Get the name of the relation route parameter.
     *
     * @return string
     */
    public function getRelationParameter()
    {
        return 'relation';
    }

    /**
     * Get the relation of the requested resource, formatted as method.
     *
     * @return string
     */
    public function getRelationMethod()
    {
        return $this->toCamelCase($this->getRelation());
    }

    /**
     * Get the specific entity of the relation of the requested resource.
     *
     * @return string
     */
    public function getSubId()
    {
        return $this->getRouteParameter($this->getSubIdParameter());
    }

    /**
     * Get the name of the sub id route parameter.
     *
     * @return string
     */
    public function getSubIdParameter()
    {
        return 'subid';
    }
}
