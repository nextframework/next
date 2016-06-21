<?php

namespace  Next\Components\Types\String;

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
     * @return Next\Components\Types\Integer
     *  An Integer Object with the decoded value
     *
     * @throws InvalidArgumentException
     *  Thrown if, after treated, the first argument, the number to be
     *  encoded by Next\ComponentszTypes\Integer\AlphaID::encode()
     *  is not present
     */
    public function prototype() {

        list( $string, $pad, $passkey ) =
            func_get_arg( 0 ) + array( '', FALSE, NULL );

        if( empty( $string ) ) {

            throw new \InvalidArgumentException(
                'A string must be informed in order to be converted to an numeric value'
            );
        }

        return $this -> decode( $string, $pad, $passkey );
    }

    /**
     * The AlphaID Decryption routine
     *
     * @author       Kevin van Zonneveld <kevin@vanzonneveld.net>
     * @author       Simon Franz
     * @author       Deadfish
     * @author       SK83RJOSH
     * @copyright    2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
     * @license      http://www.opensource.org/licenses/bsd-license.php New BSD Licence
     * @link         http://kvz.io/blog/2009/06/10/create-short-ids-with-php-like-youtube-or-tinyurl/
     *
     * @param  string|Next\Components\Types\String  $input
     *  The string to be decoded or a String Object to get string from
     *
     * @param  boolean $pad
     *  Specifies the number of minimum characters the original
     *  integer has been encoded with
     *
     * @param  string  $passkey
     *  The encryption password used to encoded the original integer
     *
     * @return Next\Components\Types\Integer
     *  An Integer Object with the decoded value
     */
    private function decode( $input, $pad = FALSE, $passkey = NULL )  {

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

        if( $input instanceof String ) $input = $input -> get();

        // Decoding

        $length = ( strlen( $input ) - 1 );

        for( $t = $length; $t >= 0; $t-- ) {

            $bcp    = bcpow( $base, ( $length - $t ) );

            $output = $output + strpos( $index, substr( $input, $t, 1 ) ) * $bcp;
        }

        // Padding Up (or down)

        if( is_numeric( $pad ) ) {

            $pad--;

            if( $pad > 0 ) $output -= pow( $base, $pad );
        }

        return new Integer( $output );
    }
}