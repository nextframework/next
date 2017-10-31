<?php

/**
 * Truncate Strings Class | Components\Types\Strings\Truncate.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace  Next\Components\Types\Strings;

use Next\Components\Interfaces\Prototypable;    # Prototypable Interface
use Next\Components\Types\Strings;              # Strings Object Class

/**
 * Generates a GUID in compliance with RFC 4122 Section 4.4
 *
 * @package    Next\Components\Types
 */
class Truncate implements Prototypable {

    /**
     * Truncate Prototyped resource controlling constants
     *
     * @var string
     */
    const TRUNCATE_BEFORE = 1;
    const TRUNCATE_AFTER  = 2;
    const TRUNCATE_CENTER = 3;
    const TRUNCATE_DEFAULT_REPLACEMENT = '...';

    // Prototypable Interface Method Implementation

    /**
     * Prototypes the Truncate routine by proxying, treating and handling
     * the mixed arguments received
     *
     * @return \Next\Components\Types\Strings
     *  A new Strings Object with the original string chopped and combined
     *  with interpolation string
     *
     * @see Truncate::truncate()
     */
    public function prototype() : Strings {

        list( $string, $length, $breakpoint, $replacement ) =
            func_get_arg( 0 ) + [ NULL, NULL, self::TRUNCATE_CENTER, self::TRUNCATE_DEFAULT_REPLACEMENT ];

        return $this -> truncate( $string, $length, $breakpoint, trim( $replacement ) );
    }

    /**
     * Truncates a string before, after or in the middle of a given length,
     * without breaking words and interpolating a custom string at the breakpoint
     *
     * @param  string $string
     *  String to be truncated
     *
     * @param  integer $length
     *  Number of characters before, after or surrounding the string
     *
     * @param  integer $breakpoint
     *  One of the three available breakpoints strategies to truncate the string
     *  before, after or in the middle
     *
     * @param  string $replacement
     *  A custom string to interpolate the string truncated, before, after or
     *  in the middle. E.g:
     *
     * $string = new Strings(
     *
     *     [ 'value' => 'Lorem ipsum labore ad in consequat laboris in mollit
     *                   fugiat et do laborum aliqua laborum mollit amet laborum
     *                   duis irure irure ut aute pariatur pariatur duis dolore
     *                   in sed nisi occaecat officia nisi et esse ut magna et.'
     *     ]
     * );
     *
     *  # Truncating to 30 characters, interpolating the string before the length
     *  #
     *  # The 30th character, including the interpolation string, occurs in the
     *  # word 'consequat'. Because we defined to truncate before the length
     *  # we walk backwards after the preceding word 'in'
     *
     * `echo $string -> truncate( 30, Strings::TRUNCATE_BEFORE ) -> get()`
     *
     *  # Truncating to 30 characters, interpolating the string after the length
     *  #
     *  # Here, because we've defined the truncating breakpoint to be after the
     *  # length we walk forward, "waiting" for the word 'consequat' to end
     *  # and only then we add the interpolation string
     *
     * `echo $string -> truncate( 30, Strings::TRUNCATE_AFTER ) -> get()`
     *
     * # Truncating to 30 characters, interpolating the string in the middle
     * #
     * # Here we define that the whole input string must must be truncated at
     * # 30 characters and that the interpolation string must be placed in the
     * # middle of it
     * # In theory, that should be 'Lorem ipsum...esse ut magna et', but we
     * # can't chop off the final dot so, 31 characters
     *
     * `echo $string -> truncate( 30, Strings::TRUNCATE_CENTER ) -> get()`
     *
     * @return \Next\Components\Types\Strings
     *  A new Strings Object with the original string chopped and combined
     *  with interpolation string
     */
    private function truncate( string $string, int $length, int $breakpoint, string $replacement ) : Strings {

        if( mb_strlen( $string ) <= $length ) {
            return new Strings( [ 'value' => $string ] );
        }

        switch( $breakpoint ) {

            case self::TRUNCATE_BEFORE:

                return new Strings(
                    [ 'value' => substr( $string, 0, strrpos( substr( $string, 0, $length ), ' ' ) ) . $replacement ]
                );

            break;

            case self::TRUNCATE_AFTER:

                return new Strings(
                    [ 'value' => substr( $string, 0, ( strpos( substr( $string, $length ),' ' ) + $length ) ) . $replacement ]
                );

            break;

            case self::TRUNCATE_CENTER:

                $len = (int) ( ( $length - mb_strlen( $replacement ) ) / 2 );

                // Separate the output from wordwrap() into an array of lines

                $segments = explode( "\n", wordwrap( $string, $len ) ) ;

                /**
                 * @internal
                 *
                 * Last element's length is less than half $len, append
                 * words from the second-last element
                 */
                $end = end( $segments );

                /**
                 * @internal
                 *
                 * Add words from the second-last line until the end is at least
                 * half as long as $length
                 */
                if( mb_strlen( $end ) <= ( $length / 2 ) && count( $segments ) > 2 ) {

                    $prev = explode( ' ', prev( $segments ) );

                    while( mb_strlen( $end ) <= ( $length / 2 ) ) {
                        $end = sprintf( '%s %s', array_pop( $prev ), $end );
                    }
                }

                return new Strings(
                    [ 'value' => reset( $segments ) . $replacement . trim( $end ) ]
                );

            break;
        }
    }
}