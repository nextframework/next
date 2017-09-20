<?php

/**
 * Prototypical Components Interface | Components\Interfaces\Prototypical.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Interfaces;

use Next\Components\Object;    # Object Class

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
     * @param \Next\Components\Object|string
     *  The name of the Object receiving the prototyped resource
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
    public function implement( $prototype, $name, $callable, $args = [] );

    /**
     * Get Prototyped Resources
     */
    public function getPrototypes();
}