<?php

/**
 * Types Component "Integer" Type Class | Math\Integer.php
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

use Next\Components\Types\Strings;    # Strings Data-type Class

/**
 * The Integer Data-type with prototypes of external/custom resources
 *
 * @package    Next\Math
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Components\Types\Strings
 *             Next\Math\Number
 *             Next\Math\Number\Prototypes\AlphaID
 */
class Integer extends Number {

    // Verifiable Interface Method Overwriting

    /**
     * Verifies Object Integrity.
     * Checks whether or not given value is acceptable by data-type class
     *
     * @throws Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if Parameter Option 'value' is not a integer -OR- is NULL
     *
     * @see \Next\Math\Number
     */
    public function verify() : void {

        parent::verify();

        if( ! is_int( $this -> options -> value ) ||
                is_float( $this -> options -> value ) ) {

            throw new InvalidArgumentException(
                'Argument is not a valid Integer'
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

        $this -> implement(
            $this, 'convert', [ $this, 'convert' ], $this -> _value
        );

        // Custom Prototypes

        $this -> implement( $this, 'AlphaID', 'Next\Math\Prototypes\AlphaID', $this -> _value );
    }

    // Custom/Adapter Prototypes

    /**
     * Convert a number between arbitrary bases - Implementation of base_convert()
     *
     * @param string $number
     *  The number to convert
     *
     * @param integer $from
     *  The base to which input integer should be converted to
     *
     * @param integer $to
     *  The base to convert number to
     *
     * @return \Next\Components\Types\Strings
     *  Strings Object with input value converted to chosen base
     */
    protected function convert( string $number, int $from, int $to ) : Strings {
        return new Strings( [ 'value' => base_convert( $number, $from, $to ) ] );
    }
}