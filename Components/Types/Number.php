<?php

namespace Next\Components\Types;

/**
 * Number Datatype Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Number extends AbstractTypes {

    // Abstract Methods Implementation

    /**
     * Check whether or not given value is acceptable by datatype class
     *
     * @param mixed $value
     *   Value to set
     *
     * @return boolean
     *   TRUE if given value is a number, by checking its type is
     *   an integer or a floating point number, and FALSE otherwise
     */
    protected function accept( $value ) {

        $type = gettype( $value );

        return ( $type == 'integer' || $type == 'double' );
    }

    /**
     * Prototype resources to object
     *
     * @param mixed|optional $value
     *   An optional value to be used by prototyped resource
     */
    protected function prototype( $n = NULL ) {

        // Prototypes which don't need value

        $this -> implement( 'max',        'max' )
              -> implement( 'min',        'min' )
              -> implement( 'modulus',    'fmod',    $n )
              -> implement( 'pow',        'pow' )
              -> implement( 'rand',       'mt_rand' );

        if( ! is_null( $n ) ) {

            $this -> implement( 'ceil',       'ceil',             $n )
                  -> implement( 'floor',      'floor',            $n )
                  -> implement( 'format',     'number_format',    $n )
                  -> implement( 'round',      'round',            $n );

            $this -> implement(

                'compare',

                function( $n2 ) use( $n ) {

                    if( $n === $n2 ) return 0;

                    return ( $n < $n2 ? -1 : 1 );
                }
            );

            $this -> implement(

                'format',

                function( $prec = 0, $dec = '.', $ts = ',' ) use( $n ) {

                    return new String( number_format( $n, $prec, $dec, $ts ) );
                }
            );
        }
    }
}