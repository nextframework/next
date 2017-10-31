<?php

/**
 * File/Directory Removal Prototype Class | FileSystem\Prototypes\Delete.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace  Next\FileSystem\Prototypes;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Interfaces\Prototypable;    # Prototypable Interface

/**
 * Copies all files from given directory to another one
 *
 * @package    Next\FileSystem
 *
 * @uses       Next\Components\Interfaces\Prototypable
 *             Next\Exception\Exceptions\InvalidArgumentException
 */
class Copy implements Prototypable {

    // Prototypable Interface Method Implementation

    /**
     * Prototypes the Directory Copy routine by proxying, treating and
     * handling the mixed arguments received
     *
     * @see Copy::copy()
     */
    public function prototype() : void {

        list( $source, $destination ) = func_get_arg( 0 ) + [ NULL, NULL ];

        $this -> copy( $source, $destination );
    }

    /**
     * Copy files and folders recursively
     *
     * @param string $source
     *  Path to the source file
     *
     * @param string $destination
     *  Destination path
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if informed directory is not resolvable (i.e. doesn't exist)
     */
    private function copy( string $source, string $destination ) : void {

        if( stream_resolve_include_path( $source ) === FALSE ) {

            throw new InvalidArgumentException(
                sprintf( 'Directory <strong>%s</strong> cannot be traversed', $directory )
            );
        }

        $directory = opendir( $source );

        if( ! is_dir( $destination ) ) mkdir( $destination );

        while( ( $file = readdir( $directory ) ) !== FALSE ) {

            $from = sprintf( '%s/%s', $source, $file );
            $to   = sprintf( '%s/%s', $destination, $file );

            if( ( $file != '.' ) && ( $file != '..' ) ) {

                if( is_dir( $from ) ) {
                    $this -> copy( $from, $to ); return;
                }

                copy( sprintf( '%s/%s', $source, $file ), $to );
            }
        }

        closedir( $directory );
    }
}