<?php

/**
 * Strings Replacement Class | Components\Types\Strings\Replace.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace  Next\Components\Types\Strings;

use Next\Components\Interfaces\Prototypable;    # Prototypable Interface
use Next\Components\Types\Strings;              # Strings Data-type Class

/**
 * The Replace Prototype translates characters in the input string,
 * replacing them
 *
 * @package    Next\Components\Types
 *
 * @uses       Next\Components\Interfaces\Prototypable
 *             Next\Components\Types\Strings
 */
class Replace implements Prototypable {

    // Prototypable Interface Method Implementation

    /**
     * Prototypes the Replace routine by proxying, treating and handling
     * the mixed arguments received
     *
     * @return \Next\Components\Types\Strings
     *  A Strings Data-type Object with all occurrences of what's
     *  being looked for replaced with what's ha been defined
     *
     * @see Replace::replace()
     */
    public function prototype() : Strings {

        list( $string, $search, $replacement ) =
            func_get_arg( 0 ) + [ NULL, NULL, NULL ];

        return $this -> replace( $string, $search, $replacement );
    }

    /**
     * Translate characters or replace substrings - Implementation of strtr()
     *
     * @param string $string
     *  The input string
     *
     * @param string $search
     *  The value being searched for
     *
     * @param string $replacement
     *  The replacement value for what's being looked for
     *
     * @return \Next\Components\Types\Strings
     *  A Strings Data-type Object with all occurrences of what's
     *  being looked for replaced with what's ha been defined
     */
    private function replace( string $string, string $search, string $replacement ) : Strings {

        return new Strings(
            [ 'value' => strtr( $string, [ $search => $replacement ] ) ]
        );
    }
}