<?php

/**
 * Types Component "Number" Type Class | Components\Types\Number.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Types;

/**
 * Defines the Number Data-type Type and prototypes some o PHP Integer
 * functions (some in different argument order) and some external/custom
 * resources as well
 *
 * @package    Next\Components\Types
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

    // Prototypable Method Implementation

    /**
     * Prototypes a custom, and maybe complex, routine to one of
     * the available types by proxying, treating and handling
     * the mixed arguments received
     */
    public function prototype() {

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