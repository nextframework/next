<?php

/**
 * Types Component "Integer" Type Class | Components\Types\Integer.php
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
 * Defines the Integer Data-type Type and prototypes some o PHP Integer
 * functions (some in different argument order) and some external/custom
 * resources as well
 *
 * @package    Next\Components\Types
 */
class Integer extends Number {

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity.
     * Checks whether or not given value is acceptable by data-type class
     *
     * @throws Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if Parameter Option 'value' is not a integer -OR- is NULL
     */
    public function verify() {

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
    public function prototype() {

        // Copying parent's prototypes

        parent::prototype();

        $this -> implement(

            $this, 'convert',

            function( $number, $from, $to ) {
                return new String( [ 'value' => base_convert( $number, $from, $to ) ] );
            },

            $this -> _value
        );

        // Custom Prototypes

        $this -> implement( $this, 'AlphaID', new Integer\AlphaID, $this -> _value );
    }
}