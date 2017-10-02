<?php

/**
 * Contextualizable Components Interface | Components\Interfaces\Contextualizable.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Interfaces;

use Next\Components\Invoker;

/**
 * Contextualizable Objects allow their context to be extended,
 * almost like PHP 5.4 Traits
 *
 * @package    Next\Components\Interfaces
 */
interface Contextualizable {

    /**
     * Registers a new Invoker Object to be used as context extension
     *
     * @param \Next\Components\Invoker $invoker
     *  Invoker Object
     *
     * @param string|array|optional $methods
     *  One or more methods accessible through extended Context.
     *  Defaults to NULL, which means almost all PUBLIC methods will be accessible
     */
    public function extend( Invoker $invoker, $methods = NULL );

    /**
     * Get Context Callables
     */
    public function getCallables();
}