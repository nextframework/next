<?php

namespace Next\Components\Types;

/**
 * Float Datatype Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
final class Float extends Number {

	// Abstract Methods Implementation

    /**
     * Check whether or not given value is acceptable by datatype class
     *
     * @param mixed $value
     *   Value to set
     *
     * @return boolean
     *   TRUE if given value is of the type integer and FALSE otherwise
     */
    protected function accept( $value ) {
        return ( gettype( $value ) != 'integer' );
    }
}