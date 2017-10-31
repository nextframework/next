<?php

/**
 * Strings Splitting Class | Components\Types\Strings\Explode.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace  Next\Components\Types\Strings;

use Next\Components\Interfaces\Prototypable;    # Prototypable Interface

/**
 * The Explode Prototype splits the input string by a delimiter string
 *
 * @package    Next\Components\Types
 *
 * @uses       Next\Components\Interfaces\Prototypable
 */
class Explode implements Prototypable {

    // Prototypable Interface Method Implementation

    /**
     * Prototypes the Explode routine by proxying, treating and handling
     * the mixed arguments received
     *
     * @return array
     *  The input string split in an array of N indexes
     */
    public function prototype() : array {

        list( $string, $delimiter, $limit ) =
            func_get_arg( 0 ) + [ NULL, NULL, PHP_INT_MAX ];

        return $this -> explode( $string, $delimiter, $limit );
    }

    /**
     * Split a string by string - Implementation of explode()
     *
     * @param string $string
     *  The input string
     *
     * @param string $delimiter
     *  The boundary string
     *
     * @param integer|optional $limit
     *  Flag controlling how many time the splitting should happen
     *
     * @return array
     *  The input string split in an array of N indexes
     */
    private function explode( string $string, string $delimiter, int $limit = PHP_INT_MAX ) : array {
        return explode( $delimiter, $string, $limit );
    }
}