<?php

/**
 * Prototypical Components Interface | Components\Interfaces\Prototypical.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Components\Interfaces;

/**
 * Prototypical Objects are assumed to allow all of their instances
 * have callable features available
 *
 * @package    Next\Components\Interfaces
 */
interface Prototypical  {

    /**
     * Implement a new callable resource, prototyping it to Object
     *
     * @param string $name
     *  Callable resource name. Should be unique
     *
     * @param callable $callable
     *  Callable resource
     *
     * @param array $args
     *  Default arguments available to callable resource
     */
    public function implement( $name, $callable, $args = array() );

    /**
     * Get Prototyped Resources
     */
    public function getPrototypes();
}