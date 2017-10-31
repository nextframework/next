<?php

/**
 * Types Component "Number" Type Class | Math\Number.php
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

use Next\Components\Types\AbstractTypes;    # Data-type Abstract Class
use Next\Components\Types\Strings;          # Strings Data-type Class

/**
 * The Number Data-type with prototypes of external/custom resources
 *
 * @package    Next\Math
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Components\Types\AbstractTypes
 *             Next\Components\Types\Strings
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
    public function verify() : void {

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
    public function prototype() : void {

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
            $this, 'compare', [ $this, 'compare' ], $this -> _value
        );

        $this -> implement( $this, 'filesize', 'Next\Math\Prototypes\FileSize', $this -> _value );
    }

    // Custom/Adapter Prototypes

    /**
     * Compare two values - Implementation of the spaceship operator
     *
     * @param integer|float $a
     *  Left comparison value
     *
     * @param integer|float $b
     *  Right comparison value
     *
     * @return integer
     *  Return  -1, 0 or 1 if `$a` is less than, equal to, or
     *  greater than `$b`, respectively
     */
    protected function compare( $a, $b ) : int {
        return ( $a <=> $b );
    }
}