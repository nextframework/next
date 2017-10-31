<?php

/**
 * Decorator Interface | Components\Decorator\Decorator.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Decorators;

/**
 * An Interface for all Resource Decorators
 *
 * @package    Next\Components\Decorators
 */
interface Decorator {

    /**
     * Decorate Resource
     */
    public function decorate();

    /**
     * Get decorated resource
     */
    public function getResource();
}