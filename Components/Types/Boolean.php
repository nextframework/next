<?php

/**
 * Types Component "Boolean" Type Class | Components\Types\Boolean.php
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
 * The Boolean Data-type with prototypes of external/custom resources
 *
 * @package    Next\Components\Types
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Components\Types\AbstractType
 */
class Boolean extends AbstractTypes {

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity.
     * Checks whether or not given value is acceptable by data-type class
     *
     * @throws Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if Parameter Option 'value' is not a boolean -OR- is NULL
     */
    public function verify() : void {

        if( $this -> options -> value === NULL ||
                ! is_bool( $this -> options -> value ) ) {

            throw new InvalidArgumentException(
                'Argument is not a valid Boolean'
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

        // Prototypes that requires a value to work with

        $this -> implement(
            $this, 'compare', [ $this, 'compare' ], $this -> _value
        );
    }

    // Custom/Adapter Prototypes

    /**
     * Compare two values
     *
     * @param mixed $a
     *  Left comparison value
     *
     * @param mixed $b
     *  Right comparison value
     *
     * @return boolean
     *  Return TRUE if the first value is equal to the second value boolean-wise
     */
    protected function compare( $a, $b ) : bool {
        return ( (bool) $a === (bool) $b );
    }
}