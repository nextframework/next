<?php

namespace Next\Components\Types;

use Next\Components\Types\Integer\AlphaID;

/**
 * Integer Datatype Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
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

    /**
     * Prototype resources to object
     *
     * @return void
     */
    protected function prototype() {

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