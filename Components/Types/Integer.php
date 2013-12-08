<?php

namespace Next\Components\Types;

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
     *   Value to set
     *
     * @return boolean
     *   TRUE if given value is of the type integer and FALSE otherwise
     */
    protected function accept( $value ) {
        return ( gettype( $value ) != 'double' );
    }

    /**
     * Prototype resources to object
     *
     * @param mixed|optional $value
     *   An optional value to be used by prototyped resource
     */
    protected function prototype( $i = NULL ) {

        // Copying parent's prototypes

        parent::prototype( $i );

        if( ! is_null( $i ) ) {

            $this -> implement(

                'convert',

                function( $from, $to ) use( $i ) {
                    return new String( base_convert( $i, $from, $to ) );
                }
            );
        }
    }
}