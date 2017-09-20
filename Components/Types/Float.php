<?php

/**
 * Types Component "Float" Type Class | Components\Types\Float.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Types;

/**
 * InvalidArgumentException Class
 */
use Next\Exception\Exceptions\InvalidArgumentException;

/**
 * Defines the Float Data-type Type
 *
 * @package    Next\Components\Types
 */
class Float extends Number {

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity.
     * Checks whether or not given value is acceptable by data-type class
     *
     * @throws Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if Parameter Option 'value' is not a float -OR- is NULL
     */
    public function verify() {

        parent::verify();

        if( ! is_float( $this -> options -> value ) ||
                is_int( $this -> options -> value ) ) {

            throw new InvalidArgumentException(

                sprintf(

                    'Argument <strong>%s</strong> is not a valid Float',

                    ( $this -> options -> value !== NULL ? $this -> options -> value : 'NULL' )
                )
            );
        }
    }
}