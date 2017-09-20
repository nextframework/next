<?php

/**
 * String Pad Class | Components\Types\String\Pad.php
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
class Pad implements Prototypable {

    // Prototypable Interface Method Implementation

    /**
     * Prototypes the String Padding routine by proxying, treating and
     * handling the mixed arguments received
     *
     * @return string
     *  Input string multi-byte safely padded with chosen character
     *
     * @see https://stackoverflow.com/a/27194169/5613506
     *  Original source authored by "Wes"
     */
    public function prototype() {

        list( $string, $length, $pad, $direction, $encoding ) =
            func_get_arg( 0 ) + [ NULL, NULL, ' ', String::PAD_RIGHT, mb_internal_encoding() ];

        if( $length === NULL || $length <= mb_strlen( $string ) ) {
            return new String( [ 'value' => $string ] );
        }

        $paddingToLeft  = $direction === String::PAD_BOTH || $direction === String::PAD_LEFT;
        $paddingToRight = $direction === String::PAD_BOTH || $direction === String::PAD_RIGHT;

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

        return new String( [ 'value' => $result ] );
    }
}