<?php

namespace Next\Components\Interfaces;

use Next\Components\Invoker;

/**
 * Contextualizable Interface
 *
 * Contextualizable Objects are assumed to allow their context to be extended,
 * almost like PHP 5.4 Traits
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Contextualizable {

    /**
     * Register a new Invoker Object to be used as context extension
     *
     * @param Next\Components\Invoker $invoker
     *   Invoker Object
     *
     * @param string|array|optional $methods
     *   One or more methods accessible through extended Context.
     *
     *   If NULL (default) all Object methods (respecting the filtering
     *   conditions) will be accessible
     */
    public function extend( Invoker $invoker, $methods = NULL );

    /**
     * Get Context Callables
     */
    public function getCallables();
}