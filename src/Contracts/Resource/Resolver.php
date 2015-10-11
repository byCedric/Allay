<?php

/*
 * This file is part of the Allay package.
 *
 * (c) Cedric van Putten <me@bycedric.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ByCedric\Allay\Contracts\Resource;

interface Resolver
{
    /**
     * Get the requested resource name.
     *
     * @return string
     */
    public function getResource();

    /**
     * Get the name of the resource route parameter.
     *
     * @return string
     */
    public function getResourceParameter();

    /**
     * Get the requested resource identifier.
     *
     * @return string
     */
    public function getId();

    /**
     * Get the name of the id route parameter.
     *
     * @return string
     */
    public function getIdParameter();

    /**
     * Get the relation of the requested resource.
     *
     * @return string
     */
    public function getRelation();

    /**
     * Get the name of the relation route parameter.
     *
     * @return string
     */
    public function getRelationParameter();

    /**
     * Get the specific entity of the relation of the requested resource.
     *
     * @return string
     */
    public function getSubId();

    /**
     * Get the name of the sub id route parameter.
     *
     * @return string
     */
    public function getSubIdParameter();
}
