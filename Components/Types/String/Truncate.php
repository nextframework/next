<?php

/**
 * Truncate String Class | Components\Types\String\GUID.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace  Next\Components\Types\String;

use Next\Components\Interfaces\Prototypable;    # Prototypable Interface
use Next\Components\Types\String;               # String Object Class

/**
 * Generates a GUID in compliance with RFC 4122 Section 4.4
 *
 * @package    Next\Components\Types
 */
class Truncate implements Prototypable {

    // Prototypable Interface Method Implementation

    /**
     * Prototypes the Truncate routine by proxying, treating and handling
     * the mixed arguments received
     *
     * @return \Next\Components\Types\String
     *  A new String Object with the original string chopped
     *  and combined with delimiter string
     */
    public function prototype() {

        list( $string, $length, $breakpoint, $replacement ) =
            func_get_arg( 0 ) + [ NULL, NULL, String::TRUNCATE_CENTER, String::TRUNCATE_DEFAULT_REPLACEMENT ];

        if( strlen( $string ) <= $length ) return $string;

        switch( $breakpoint ) {

            case String::TRUNCATE_BEFORE:

                return new String(
                    substr( $string, 0, strrpos( substr( $string, 0, $length ), ' ' ) ) . $replacement
                );

            break;

            case String::TRUNCATE_AFTER:

                return new String(
                    substr( $string, 0, ( strpos( substr( $string, $length ),' ' ) + $length ) ) . $replacement
                );

            break;

            case String::TRUNCATE_CENTER:

                $replacement = sprintf( ' %s ', trim( $replacement ) );

                $len = (int) ( ( $length - strlen( $replacement ) ) / 2 );

                // Separate the output from wordwrap() into an array of lines

                $segments = explode( "\n", wordwrap( $string, $len ) ) ;

                /**
                 * Last element's length is less than half $len, append
                 * words from the second-last element
                 */
                $end = end( $segments );

                /**
                 * Add words from the second-last line until the end is at least
                 * half as long as $length
                 */
                if( strlen( $end ) <= ( $length / 2 ) && count( $segments ) > 2 ) {

                    $prev = explode( ' ', prev( $segments ) );

                    while( strlen( $end ) <= ( $length / 2 ) ) {
                        $end = sprintf( '%s %s', array_pop( $prev ), $end );
                    }
                }

                return new String(
                    reset( $segments ) . $replacement . trim( $end )
                );

            break;
        }
    }
}