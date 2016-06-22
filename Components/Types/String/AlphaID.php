<?php

namespace  Next\Components\Types\String;

use Next\Components\Interfaces\Prototyped;    # Prototyped Interface

use Next\Components\Types\Integer;            # Integer Object Class
use Next\Components\Types\String;             # String Object Class

/**
 * AlphaID Decoding Prototype Routine
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2016 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class AlphaID implements Prototyped {

    /**
     * Decoding Character Set
     *
     * @var string
     */
    const SET = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Decoding Character Set
     *
     * @var string
     */
    const SET = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    // Prototyped Interface Method Implementation

    /**
     * Prototypes the AlphaID routine by proxying, treating and handling
     * the mixed arguments received
     *
     * @return Next\Components\Types\Integer
     *  An Integer Object with the decoded value
     *
     * @return Next\Components\Types\Integer
     *  An Integer Object with the AlphaID decoding results
     *
     * @throws InvalidArgumentException
     *  Thrown if, after treated, the first argument, the number to be
     *  decoded by Next\Components\Types\String\AlphaID::decode()
     *  is not present
     *
     * @throws InvalidArgumentException
     *  Thrown if the alternative character set is not a string
     *
     * @see Next\Components\Types\String\AlphaID::decode()
     */
    public function prototype() {

        list( $string, $index ) =
            func_get_arg( 0 ) + array( '', self::SET );

        if( $string instanceof String ) $string = $string -> get();

        if( empty( $string ) ) {

            throw new \InvalidArgumentException(
                'A string must be informed in order to be converted to an numeric value'
            );
        }

        if( ! is_string( $index ) ) {
            throw new \InvalidArgumentException( 'The Decoding sequence must be a string' );
        }

        return $this -> decode( $string, $index );
    }

    /**
     * The AlphaID Decryption routine
     *
     * @author    "poops"
     * @link      https://github.com/poops/php-classes/blob/master/AlphaId.php
     *
     * @param  string|Next\Components\Types\String  $input
     *  The string to be decoded or a String Object to get string from
     *
     * @param  string $index
     *  An alternative set of characters to perform the encoding process
     *
     * @return integer
     *  The input string decoded back to its integer value
     */
    private function decode( $input, $index ) {

        $base   = strlen( $index );
        $output = 0;
        $length = strlen( $input ) - 1;

        for( $i = 0; $i <= $length; $i++ ) {

            $bcpow   =  bcpow( $base, $length - $i );

            $output += strpos( $index, substr( $input, $i, 1 ) ) * $bcpow;
        }

        $output -= pow( $base, 4 );

        $output = sprintf( '%F', $output );
        $output = substr( $output, 0, strpos( $output, '.' ) );

        return (int) $output;
    }
}