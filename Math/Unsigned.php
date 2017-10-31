<?php

/**
 * Types Component "Unsigned" Type Class | Math\Unsigned.php
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
 * The Unsigned Data-type Type with prototypes of external/custom resources
 *
 * @package    Next\Math
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Math\Number
 */
class Unsigned extends Number {

    // Verifiable Interface Method Overwriting

    /**
     * Verifies Object Integrity.
     * Checks whether or not given value is acceptable by data-type class
     *
     * Note that there is no signed–unsigned type distinction.
     * Here, Unsigned just represents a valid non-negative
     * \Next\Math\Number
     *
     * @throws Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if Parameter Option 'value' is not is not a positive
     *  Number, one of the characteristics of Unsigned Numbers
     *
     * @see \Next\Math\Number::verify()
     */
    public function verify() : void {

        parent::verify();

        if( $this -> options -> value < 0 ) {

            throw new InvalidArgumentException(
                'Argument is not a valid Unsigned Number'
            );
        }
    }
}