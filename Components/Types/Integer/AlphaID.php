<?php

namespace  Next\Components\Types\Integer;

use Next\Components\Interfaces\Prototyped;

use Next\Components\Types\Integer;
use Next\Components\Types\String;

class AlphaID implements Prototyped {

    // Prototyped Interface Method Implementation

    /**
     * ReceivesPrototypes a custom, and maybe complex, routine to one of
     * the available types by proxying, treating and handling
     * the mixed arguments received
     *
     * @throws InvalidArgumentException
     *  Thrown if, after treated, the first argument, the number to be
     *  encoded by Next\ComponentszTypes\Integer\AlphaID::encode()
     *  is not present
     */
    public function prototype() {

        list( $integer, $pad, $passkey ) =
            func_get_arg( 0 ) + array( NULL, FALSE, NULL );

        if( is_null( $integer ) ) {

            throw new \InvalidArgumentException(
                'An integer must be informed in order to be converted to an AlphaID string'
            );
        }

        return $this -> encode( $integer, $pad, $passkey );
    }

    /**
     * The AlphaID Encryption routine
     *
     * @author       Kevin van Zonneveld <kevin@vanzonneveld.net>
     * @author       Simon Franz
     * @author       Deadfish
     * @author       SK83RJOSH
     * @copyright    2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
     * @license      http://www.opensource.org/licenses/bsd-license.php New BSD Licence
     * @link         http://kvz.io/blog/2009/06/10/create-short-ids-with-php-like-youtube-or-tinyurl/
     *
     * @param  string|Next\Components\Types\Integer  $input
     *  The integer to be encoded or an Integer Object to get integer from
     *
     * @param  boolean $pad
     *  Specifies the number of minimum characters for the resulting string
     *
     * @param  string  $passkey
     *  An encryption password to encode
     *
     * @return Next\Components\Types\Integer
     *  A String Object with the encoded value
     */
    private function encode( $input, $pad = FALSE, $passkey = NULL ) {

        $output   =   '';
        $index    = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base     = strlen( $index );

        if( $passkey !== NULL ) {

            /**
             * @internal
             *
             * Although this function's purpose is to just make the
             * ID short - and not so much secure,
             * with this patch by Simon Franz (http://blog.snaky.org/)
             * you can optionally supply a password to make it harder
             * to calculate the corresponding numeric ID
             */
            for( $n = 0; $n < strlen( $index ); $n++ ) {
                $i[] = substr( $index, $n, 1 );
            }

            $hash = hash( 'sha256', $passkey );

            if( strlen( $hash ) < strlen( $index ) ) {
                $hash = hash( 'sha512', $passkey );
            }

            for( $n = 0; $n < strlen( $index ); $n++ ) {
                $p[] =  substr( $hash, $n, 1 );
            }

            array_multisort( $p, SORT_DESC, $i );

            $index = implode( $i );
        }

        if( $input instanceof Integer ) $input = $input -> get();

        // Padding Up

        if( is_numeric( $pad ) ) {

            $pad--;

            if( $pad > 0 ) $input += pow( $base, $pad );
        }

        // Encoding

        for( $t = ( $input != 0 ? floor( log( $input, $base ) ) : 0 ); $t >= 0; $t-- ) {

            $bcp       = bcpow( $base, $t );
            $a         = floor( $input / $bcp ) % $base;
            $output    = $output . substr( $index, $a, 1 );
            $input     = ( $input - ( $a * $bcp ) );
        }

        return new String( $output );
    }
}