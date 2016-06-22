<?php

namespace  Next\Components\Types\Integer;

use Next\Components\Interfaces\Prototyped;    # Prototyped Interface

use Next\Components\Types\Integer;            # Integer Object Class
use Next\Components\Types\String;             # String Object Class

/**
 * AlphaID Encoding Prototype Routine
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2016 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class AlphaID implements Prototyped {

    /**
     * Encoding Character Set
     *
     * @var string
     */
    const SET = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Encoding Character Set
     *
     * @var string
     */
    const SET = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    // Prototyped Interface Method Implementation

    /**
     * Prototypes the AlphaID routine by proxying, treating and handling
     * the mixed arguments received
     *
     * @return Next\Components\Types\String
     *  A String Object with the AlphaID encoding results
     *
     * @throws InvalidArgumentException
     *  Thrown if, after treated, the first argument, the number to be
     *  encoded by Next\Components\Types\Integer\AlphaID::encode()
     *  is not present
     *
     * @throws InvalidArgumentException
     *  Thrown if the argument informed as alternative character set is not a string
     *
     * @see Next\Components\Types\Integer\AlphaID::encode()
     */
    public function prototype() {

        list( $integer, $index ) =
            func_get_arg( 0 ) + array( NULL, self::SET );

        if( $integer instanceof Integer ) $integer = $integer -> get();

        if( is_null( $integer ) ) {

            throw new \InvalidArgumentException(
                'An integer must be informed in order to be converted to an AlphaID string'
            );
        }

        if( ! is_string( $index ) ) {
            throw new \InvalidArgumentException( 'The Decoding sequence must be a string' );
        }

        return new String( $this -> encode( $integer, $index ) );
    }

    /**
     * The AlphaID Encryption routine
     *
     * @author    "poops"
     * @link      https://github.com/poops/php-classes/blob/master/AlphaId.php
     *
     * @param  string|Next\Components\Types\Integer  $input
     *  The integer to be encoded or an Integer Object to get integer from
     *
     * @param  string $index
     *  An alternative set of characters to perform the decoding process
     *
     * @return string
     *  The input integer encoded as an AlphaID string
     */
    private function encode( $input, $index ) {

        $base    = strlen( $index );
        $input  += pow( $base, 4 );
        $output  = '';

        for( $i = floor( log( $input, $base ) ); $i >= 0; $i-- ) {

            $bcp     = bcpow( $base, $i );
            $start   = floor( $input / $bcp ) % $base;
            $output .= substr( $index, $start, 1 );
            $input   = $input - ( $start * $bcp );
        }

        return $output;
    }
}