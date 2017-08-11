<?php

/**
 * AlphaID Encoding Prototypable Class | Components\Types\Integer\AlphaID.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace  Next\Components\Types\Integer;

use Next\Components\Interfaces\Prototypable;    # Prototypable Interface

use Next\Components\Types\Integer;              # Integer Object Class
use Next\Components\Types\String;               # String Object Class

/**
 * Creates a \Next\Components\Types\String with the value of a
 * \Next\Components\Types\Integer encoded into its AlphaID representation
 *
 * @package    Next\Components\Types
 */
class AlphaID implements Prototypable {

    /**
     * Encoding Character Set
     *
     * @var string
     */
    const SET = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    // Prototypable Interface Method Implementation

    /**
     * Prototypes the AlphaID routine by proxying, treating and handling
     * the mixed arguments received
     *
     * @return \Next\Components\Types\String
     *  A String Object with the AlphaID encoding results
     *
     * @throws InvalidArgumentException
     *  Thrown if, after treated, the first argument, the number to be
     *  encoded by \Next\Components\Types\Integer\AlphaID::encode()
     *  is not present
     *
     * @throws InvalidArgumentException
     *  Thrown if the argument informed as alternative character set is not a string
     *
     * @see \Next\Components\Types\Integer\AlphaID::encode()
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
     * @param string|\Next\Components\Types\Integer $input
     *  The integer to be encoded or an Integer Object to get integer from
     *
     * @param string $index
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