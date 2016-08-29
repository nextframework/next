<?php

namespace Next\Components\Types;

/**
 * Unsigned Integer Datatype Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
final class Unsigned extends Number {

    // Abstract Methods Implementation

    /**
     * Check whether or not given value is acceptable by data-type class
     *
     * The Integer data-type accepts all POSITIVE non-float numbers and the zero
     *
     * @param mixed $value
     *  Value to set
     *
     * @return boolean
     *  TRUE if given value is of the type integer and FALSE otherwise
     *
     * @see Next\Components\Types\Number::accept()
     * @see Next\Components\Types\Integer::accept()
     * @see Next\Components\Types\Float::accept()
     */
    protected function accept( $value ) {
        return parent::accept( $value ) && ( $value >= 0 );
    }
}