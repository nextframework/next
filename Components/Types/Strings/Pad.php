<?php

/**
 * Strings Pad Class | Components\Types\Strings\Pad.php
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
 *
 * @uses       Next\Components\Interfaces\Prototypable
 *             Next\Components\Types\Strings
 */
class Pad implements Prototypable {

    /**
     * String Padding resource controlling constants
     *
     * @var string
     */
    const PAD_LEFT  = 0;
    const PAD_RIGHT = 1;
    const PAD_BOTH  = 2;

    // Prototypable Interface Method Implementation

    /**
     * Prototypes the Strings Padding routine by proxying, treating and
     * handling the mixed arguments received
     *
     * @return string
     *  Input string multi-byte safely padded with chosen character
     *
     * @see https://stackoverflow.com/a/27194169/5613506
     *  Original source authored by "Wes"
     *
     * @see Pad::pad()
     */
    public function prototype() : Strings {

        list( $string, $length, $pad, $direction, $encoding ) =
            func_get_arg( 0 ) + [ NULL, NULL, ' ', self::PAD_RIGHT, mb_internal_encoding() ];

        return $this -> pad( $string, $length, $pad, $direction, $encoding );
    }

    /**
     * Pad a string to a certain length with another string
     *
     * @param string $String
     *  The input string
     *
     * @param integer $length
     *  The desired output length.
     *  If not defined or shorter than input string no padding will take place
     *
     * @param string|optional $pad
     *  The substring that'll be added as padding to input string
     *
     * @param integer|optional $direction
     *  An optional direction for the padding: Left (Pad::PAD_LEFT),
     *  Right (Pad::PAD_RIGHT) or Both (Pad::PAD_BOTH)
     *
     * @param string|optional $encoding
     *  An optional Character Encoding to be passed on to mb_strlen()
     */
    private function pad( string $string, int $length, string $pad = ' ', int $direction = self::PAD_RIGHT, string $encoding ) : Strings {

        if( $length <= mb_strlen( $string ) ) {
            return new Strings( [ 'value' => $string ] );
        }

        $paddingToLeft  = $direction === self::PAD_BOTH || $direction === self::PAD_LEFT;
        $paddingToRight = $direction === self::PAD_BOTH || $direction === self::PAD_RIGHT;

        $length       -= mb_strlen( $string, $encoding );
        $targetLength  = $paddingToLeft && $paddingToRight ? $length / 2 : $length;

        $repeatedString = str_repeat(
            $pad, max( 0, ceil( $targetLength / mb_strlen( $pad, $encoding ) ) )
        );

        $result = sprintf(

            '%2$s%1$s%3$s', $string,

            $paddingToLeft  ? mb_substr( $repeatedString, 0, floor( $targetLength ), $encoding ) : '',
            $paddingToRight ? mb_substr( $repeatedString, 0,  ceil( $targetLength ), $encoding )  : ''
        );

        return new Strings( [ 'value' => $result ] );
    }
}