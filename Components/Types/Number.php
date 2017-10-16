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
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

/**
 * Defines the Number Data-type Type and prototypes some o PHP Integer
 * functions (some in different argument order) and some external/custom
 * resources as well
 *
 * @package    Next\Components\Types
 */
class Number extends AbstractTypes {

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity.
     * Checks whether or not given value is acceptable by data-type class
     *
     * @throws Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if Parameter Option 'value' is not a number -OR- is NULL
     */
    public function verify() {

        if( $this -> options -> value === NULL ||
                ! is_numeric( $this -> options -> value ) &&
                    ! is_int( $this -> options -> value ) &&
                        ! is_float( $this -> options -> value ) ) {

            throw new InvalidArgumentException(
                'Argument is not a valid Number'
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

        $this -> implement( $this, 'max',  'max' )
              -> implement( $this, 'min',  'min' );

        $this -> implement( $this, 'rand',    'mt_rand',         $this -> _value )
              -> implement( $this, 'pow',     'pow',             $this -> _value )
              -> implement( $this, 'ceil',    'ceil',            $this -> _value )
              -> implement( $this, 'floor',   'floor',           $this -> _value )
              -> implement( $this, 'modulus', 'fmod',            $this -> _value )
              -> implement( $this, 'round',   'round',           $this -> _value )
              -> implement( $this, 'format',  'number_format',   $this -> _value );

        // Custom Functions

        $this -> implement(

            $this, 'compare', function( $a, $b ) {

                if( $a === $b ) return 0;

                return ( $a < $b ? -1 : 1 );
            },

            $this -> _value
        );

        /**
         * Formats given file size to be more human readable, by
         * converting bytes and adding the proper acronym
         *
         * ````
         * $number = new Number( [ 'value' => 572249866.24 ] );
         *
         * var_dump( $number -> filesize() -> get() ); // 545.74 MB
         * ````
         *
         * @see https://stackoverflow.com/a/2510459/5613506
         */
        $this -> implement( $this, 'filesize', function( $bytes ) {

            $units = [ 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' ];

            $bytes = max( $bytes, 0 );
            $pow   = floor( ( $bytes ? log( $bytes ) : 0 ) / log( 1024 ) );
            $pow   = min( $pow, count( $units ) - 1 );

            $bytes /= ( 1 << ( 10 * $pow ) );

            return new String(
                [ 'value' => round( $bytes, 2 ) . ' ' . $units[ $pow ] ]
            );

        }, $this -> _value );
    }
}