<?php

/**
 * AlphaID Decoding Prototypable Class | Components\Types\Strings\AlphaID.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace  Next\Components\Types\Strings;

use Next\Components\Interfaces\Prototypable;    # Prototypable Interface

use Next\Math\Integer;                          # Integer Object Class
use Next\Components\Types\Strings;              # Strings Object Class

/**
 * Creates a Next\Math\Integer with the value of a Next\Math\Prototypes\AlphaID
 * decoded into its original integer representation
 *
 * Note that the input string must have to be encoded by
 * Next\Math\Prototypes\AlphaID -AND- with the same Characters Set being used
 * to decode otherwise the process *may* fail or result in wrong values
 *
 * @package    Next\Components\Types
 *
 * @uses       Next\Components\Interfaces\Prototypable
 *             Next\Math\Integer
 *             Next\Components\Types\Strings
 */
class AlphaID implements Prototypable {

    /**
     * Decoding Character Set
     *
     * @var string
     */
    const SET = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    // Prototypable Interface Method Implementation

    /**
     * Prototypes the AlphaID routine by proxying, treating and handling
     * the mixed arguments received
     *
     * @return \Next\Math\Integer
     *  An Integer Object with the decoded value
     *
     * @throws InvalidArgumentException
     *  Thrown if, after treated, the first argument, the number to be
     *  decoded by Next\Components\Types\Strings\AlphaID::decode()
     *  is not present
     *
     * @throws InvalidArgumentException
     *  Thrown if the alternative character set is not a string
     *
     * @see \Next\Components\Types\Strings\AlphaID::decode()
     */
    public function prototype() : Integer {

        list( $string, $index ) = func_get_arg( 0 ) + [ '', self::SET ];

        if( $string instanceof Strings ) $string = $string -> get();

        if( empty( $string ) ) {

            throw new \InvalidArgumentException(
                'A string must be informed in order to be converted to a numeric value'
            );
        }

        if( ! is_string( $index ) ) {
            throw new \InvalidArgumentException( 'The Decoding sequence must be a string' );
        }

        return new Integer( [ 'value' => $this -> decode( $string, $index ) ] );
    }

    /**
     * The AlphaID Decryption routine
     *
     * @author    "poops"
     * @link      https://github.com/poops/php-classes/blob/master/AlphaId.php
     *
     * @param string $input
     *  The string to be decoded or a Strings Object to get string from
     *
     * @param string $index
     *  An alternative set of characters to perform the encoding process
     *
     * @return integer
     *  The input string decoded back to its integer value
     */
    private function decode( string $input, string $index ) : int {

        $base   = mb_strlen( $index );
        $output = 0;
        $length = mb_strlen( $input ) - 1;

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