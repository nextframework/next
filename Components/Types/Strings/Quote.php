<?php

/**
 * Strings Quoting Class | Components\Types\Strings\Quote.php
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
 * The Quote Prototype wraps the input string with another string
 *
 * @package    Next\Components\Types
 *
 * @uses       Next\Components\Interfaces\Prototypable
 *             Next\Components\Types\Strings
 */
class Quote implements Prototypable {

    // Prototypable Interface Method Implementation

    /**
     * Prototypes the Quote routine by proxying, treating and handling
     * the mixed arguments received
     *
     * @return \Next\Components\Types\Strings
     *  A Strings Data-type Object with the quoted string
     *
     * @see Quote::quote()
     */
    public function prototype() : Strings {

        list( $string, $identifier ) = func_get_arg( 0 ) + [ NULL, '"' ];

        return $this -> quote( $string, $identifier );
    }

    /**
     * Quotes a string with given Quote Identifier
     *
     * @param string $string
     *  The string to be quoted. Not directly passed!
     *
     * @param string|optional $identifier
     *  The Quote Identifier that'll wrap the input string
     *
     * @return Next\Components\Types\Strings
     *  A Strings Data-type Object with the quoted string
     */
    private function quote( string $string, string $identifier = '"' ) : Strings {

        return new Strings(
            [ 'value' => sprintf( '%1$s%2$s%1$s', $identifier, $string ) ]
        );
    }
}