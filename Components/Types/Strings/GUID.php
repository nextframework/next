<?php

/**
 * GUID Generator Prototypable Class | Components\Types\Strings\GUID.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace  Next\Components\Types\Strings;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\RuntimeException;

use Next\Components\Interfaces\Prototypable;    # Prototypable Interface
use Next\Components\Types\Strings;              # Strings Object Class

/**
 * Generates a GUID in compliance with RFC 4122 Section 4.4
 *
 * @package    Next\Components\Types
 *
 * @uses       Next\Exception\Exceptions\RuntimeException
 *             Next\Components\Interfaces\Prototypable
 *             Next\Components\Types\Strings
 */
class GUID implements Prototypable {

    // Prototypable Interface Method Implementation

    /**
     * Prototypes the GUID routine by proxying, treating and handling
     * the mixed arguments received
     *
     * @return \Next\Components\Types\Strings
     *  A Strings Object with the generated GUID
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown if OpenSSL Extension is not enabled
     *
     * @see \Next\Components\Types\Strings\GUID::GUID()
     */
    public function prototype() : Strings {

        if( ! extension_loaded( 'openssl' ) ) {

            throw new RuntimeException(
                'OpenSSL Extension is required in order to generate a GUID v4'
            );
        }

        return new Strings( [ 'value' => $this -> GUID() ] );
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
    private function GUID() : string {

        $data = openssl_random_pseudo_bytes( 16 );

        assert( strlen( $data ) == 16 );

        $data[ 6 ] = chr( ord( $data[ 6 ] ) & 0x0f | 0x40 ); // set version to 0100
        $data[ 8 ] = chr( ord( $data[ 8 ] ) & 0x3f | 0x80 ); // set bits 6-7 to 10

        return vsprintf(
            '%s%s-%s-%s-%s-%s%s%s', str_split( bin2hex( $data ), 4 )
        );
    }
}