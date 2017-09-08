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

use Next\Components\Types\Integer\AlphaID;

/**
 * Defines the Integer Data-type Type and prototypes some o PHP Integer
 * functions (some in different argument order) and some external/custom
 * resources as well
 *
 * @package    Next\Components\Types
 */
final class Integer extends Number {

    // Abstract Methods Implementation

    /**
     * Check whether or not given value is acceptable by datatype class
     *
     * @param mixed $value
     *  Value to set
     *
     * @return boolean
     *  TRUE if given value is of the type integer and FALSE otherwise
     */
    protected function accept( $value ) {
        return ( is_int( $value ) && ! is_float( $value ) );
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

        // Prototypes that requires a value to work with

        if( $this -> _value !== NULL ) {

            // Custom Functions

            $value = $this -> _value;

            $this -> implement(

                'convert',

                function( $from, $to ) use( $value ) {
                    return new String( base_convert( $value, $from, $to ) );
                }
            );

            // Custom Prototypes

            $this -> implement( 'alphaID', new AlphaID, $this -> value );
        }
    }
}