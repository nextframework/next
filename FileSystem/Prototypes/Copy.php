<?php

/**
 * File/Directory Removal Prototype Class | FileSystem\Prototypes\Delete.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace  Next\Filesystem\Prototypes;

use Next\Components\Interfaces\Prototypable;    # Prototypable Interface

/**
 * Copies all files from given directory to another one
 *
 * @package    Next\FileSystem
 */
class Copy implements Prototypable {

    // Prototypable Interface Method Implementation

    /**
     * Prototypes the Directory Copy routine by proxying, treating and
     * handling the mixed arguments received
     *
     * @return \Next\Components\Types\String
     *  A new String Object with the original string chopped
     *  and combined with delimiter string
     */
    public function prototype() {

        list( $source, $destination ) = func_get_arg( 0 ) + [ NULL, NULL ];

        return $this -> copy( $source, $destination );
    }

    /**
     * Copy files and folders recursively
     *
     * @param string $source
     *  Path to the source file
     *
     * @param string $destination
     *  Destination path
     */
    private function copy( $source, $destination ) {

        if( $source === NULL || ( $source = stream_resolve_include_path( $source ) ) === FALSE ) return;

        $directory = opendir( $source );

        if( ! is_dir( $destination ) ) mkdir( $destination );

        while( ( $file = readdir( $directory ) ) !== FALSE ) {

            $from = sprintf( '%s/%s', $source, $file );
            $to   = sprintf( '%s/%s', $destination, $file );

            if( ( $file != '.' ) && ( $file != '..' ) ) {

                if( is_dir( $from ) ) {

                    $this -> copy( $from, $to );

                } else {

                    copy( sprintf( '%s/%s', $source, $file ), $to );
                }
            }
        }

        closedir( $directory );
    }
}