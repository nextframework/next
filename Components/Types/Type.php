<?php

namespace Next\Components\Types;

/**
 * Datatype Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Type {

    /**
     * Set value
     *
     * @param mixed $value
     *   Value to set
     */
    public function set( $value );

    /**
     * Get value
     */
    public function get();
}