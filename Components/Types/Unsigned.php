<?php

/**
 * Types Component "Unsigned" Type Class | Components\Types\Unsigned.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Types;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

/**
 * Defines the Unsigned Data-type Type
 *
 * @package    Next\Components\Types
 */
class Unsigned extends Number {

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
     * @see \Next\Components\Types\Number::accept()
     * @see \Next\Components\Types\Integer::accept()
     * @see \Next\Components\Types\Float::accept()
     */
    protected function accept( $value ) {
        return parent::accept( $value ) && ( $value >= 0 );
    }

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity.
     * Checks whether or not given value is acceptable by data-type class
     *
     * Note that there is no signed–unsigned type distinction.
     * Here, Unsigned just represents a valid non-negative
     * \Next\Components\Types\Number
     *
     * @throws Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if Parameter Option 'value' is not a unsigned number -OR- is NULL
     */
    public function verify() {

        parent::verify();

        if( $this -> options -> value < 0 ) {

            throw new InvalidArgumentException(
                'Argument is not a valid Unsigned Number'
            );
        }
    }
}