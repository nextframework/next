<?php

/**
 * GUID Generator Prototypable Class | Components\Types\String\GUID.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace  Next\Components\Types\String;

use Next\Components\Debug\Exception;          # Exception Class

use Next\Components\Interfaces\Prototypable;    # Prototypable Interface

use Next\Components\Types\String;             # String Object Class

/**
 * Generates a GUID in compliance with RFC 4122 Section 4.4
 *
 * @package    Next\Components\Types
 */
class GUID implements Prototypable {

    // Prototypable Interface Method Implementation

    /**
     * Prototypes the GUID routine by proxying, treating and handling
     * the mixed arguments received
     *
     * @return \Next\Components\Types\String
     *  A String Object with the generated GUID
     *
     * @throws \Next\Components\Debug\Exception
     *  Thrown if OpenSSL Extension is not enabled
     *
     * @see \Next\Components\Types\String\GUID::GUID()
     */
    public function prototype() {

        if( ! extension_loaded( 'openssl' ) ) {

            throw new Exception(

                'OpenSSL Extension is required in order to generate a GUID v4',

                Exception::UNFULFILLED_REQUIREMENTS, NULL, 500
            );
        }

        return new String( $this -> GUID() );
    }

    /**
     * The GUID v4 routine
     *
     * @author    "Jack"
     * @link      http://stackoverflow.com/a/15875555/753531
     *
     * @return string
     *  The generated GUID v4
     */
    private function GUID() {

        $data = openssl_random_pseudo_bytes( 16 );

        assert( strlen( $data ) == 16 );

        $data[ 6 ] = chr( ord( $data[ 6 ] ) & 0x0f | 0x40 ); // set version to 0100
        $data[ 8 ] = chr( ord( $data[ 8 ] ) & 0x3f | 0x80 ); // set bits 6-7 to 10

        return vsprintf(
            '%s%s-%s-%s-%s-%s%s%s', str_split( bin2hex( $data ), 4 )
        );
    }
}