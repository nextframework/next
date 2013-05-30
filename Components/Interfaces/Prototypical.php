<?php

namespace Next\Components\Interfaces;

/**
 * Prototypical Interface
 *
 * Prototypical Objects are assumed to allow all of their instances
 * have callable features available
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Prototypical  {

    /**
     * Implement a new callable resource, prototyping it to Object
     *
     * @param string $name
     *   Callable resource name. Should be unique
     *
     * @param callable $callable
     *   Callable resource
     *
     * @param array $args
     *   Default arguments available to callable resource
     */
    public function implement( $name, $callable, $args = array() );

    /**
     * Get Prototyped Resources
     */
    public function getPrototypes();
}