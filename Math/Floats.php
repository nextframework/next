<?php

/**
 * Types Component "Floats" Type Class | Math\Floats.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Math;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

/**
 * The Float Data-type Type with prototypes of external/custom resources
 *
 * @package    Next\Math
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Math\Number
 */
class Floats extends Number {

    // Verifiable Interface Method Overwriting

    /**
     * Verifies Object Integrity.
     * Checks whether or not given value is acceptable by data-type class
     *
     * @throws Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if Parameter Option 'value' is not a float -OR- is NULL
     *
     * @see \Next\Math\Number
     */
    public function verify() : void {

        parent::verify();

        if( ! is_float( $this -> options -> value ) ||
                is_int( $this -> options -> value ) ) {

            throw new InvalidArgumentException(
                'Argument is not a valid Float'
            );
        }
    }

    // Prototypable Method Implementation

    /**
     * Prototypes a custom, and maybe complex, routine to one of
     * the available types by proxying, treating and handling
     * the mixed arguments received
     */
    public function prototype() : void {

        // Copying parent's prototypes

        parent::prototype();

        // Custom Prototypes

        $this -> implement( $this, 'pNorm', 'Next\Math\Prototypes\pNorm', $this -> _value );
    }
}