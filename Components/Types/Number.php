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
     *  Value to set
     *
     * @return boolean
     *  TRUE if given value is a number, by checking its type is
     *  an integer or a floating point number, and FALSE otherwise
     */
    protected function accept( $value ) {
        return ( is_int( $value ) || is_float( $value ) );
    }

    /**
     * Prototype resources to object
     *
     * @return void
     */
    protected function prototype() {

        // Prototypes that doesn't require an initial base value to work with

        $this -> implement( 'max',  'max'     )
              -> implement( 'min',  'min'     )
              -> implement( 'pow',  'pow'     )
              -> implement( 'rand', 'mt_rand' );

        // Prototypes that requires a value to work with

        if( $this -> _value !== NULL ) {

            // Native Functions

            $this -> implement( 'rand',       'mt_rand'       )
                  -> implement( 'ceil',       'ceil'          )
                  -> implement( 'modulus',    'fmod'          )
                  -> implement( 'floor',      'floor'         )
                  -> implement( 'format',     'number_format' )
                  -> implement( 'round',      'round'         );

            $value = $this -> _value;

            // Custom Functions

            $this -> implement(

                'compare',

                function( $n ) use( $value ) {

                    if( $value === $n ) return 0;

                    return ( $value < $n ? -1 : 1 );
                }
            );

            $this -> implement(

                'format',

                function( $prec = 0, $dec = '.', $ts = ',' ) use( $value ) {

                    return new String( number_format( $value, $prec, $dec, $ts ) );
                }
            );
        }
    }
}