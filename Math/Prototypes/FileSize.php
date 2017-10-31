<?php

/**
 * Human Readable FileSize Prototypable Class | Math\Prototypes\FileSize.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace  Next\Math\Prototypes;

use Next\Components\Interfaces\Prototypable;    # Prototypable Interface
use Next\Components\Types\Strings;              # String Data-type Class

/**
 * Formats given file size to be more human readable, by converting bytes and
 * adding the proper acronym
 *
 * @package    Next\Math
 *
 * @uses       Next\Components\Interfaces\Prototypable
 *             Next\Components\Types\Strings
 */
class FileSize implements Prototypable {

    // Prototypable Interface Method Implementation

    /**
     * Prototypes the FileSize routine by proxying, treating and handling
     * the mixed arguments received
     *
     * @return \Next\Components\Types\Strings
     *  A String Object with the human readable version of input bytes
     */
    public function prototype() : Strings {

        list( $bytes ) = func_get_arg( 0 ) + [ 0 ];

        return new Strings( [ 'value' => $this -> filesize( $bytes ) ] );
    }

    /**
     * Formats given file size to be more human readable, by
     * converting bytes and adding the proper acronym
     *
     * ````
     * $number = new Number( [ 'value' => 572249866.24 ] );
     *
     * var_dump( $number -> filesize() -> get() ); // 545.74 MB
     * ````
     *
     * @see https://stackoverflow.com/a/2510459/5613506
     *
     * @param integer|float
     *  Filesize in bytes to process
     *
     * @return string
     *  Human readable version of input value as string
     */
    private function filesize( $bytes ) : string {

        $units = [ 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' ];

        $bytes = max( $bytes, 0 );
        $pow   = floor( ( $bytes ? log( $bytes ) : 0 ) / log( 1024 ) );
        $pow   = min( $pow, count( $units ) - 1 );

        $bytes /= ( 1 << ( 10 * $pow ) );

        return sprintf( '%s %s', round( $bytes, 2 ), $units[ $pow ] );
    }
}