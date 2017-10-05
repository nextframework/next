<?php

/**
 * FileSystem "Path" Types Class | FileSystem\Path.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\FileSystem;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Types\String;    # Strings Data-type Class

/**
 * Defines the Path Data-type Type and prototypes some external/custom
 * resources related to FileSystem operations
 *
 * @package    Next\FileSystem
 */
class Path extends String {

    // Verifiable Interface Method Overwriting

    /**
     * Verifies Object Integrity.
     * Checks whether or not given value is acceptable by data-type class
     *
     * @throws Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if Parameter Option 'value' is not a valid string,
     *  is NULL or is not a resolvable filepath
     *
     * @see Next\Components\Types\String::verify()
     */
    public function verify() {

        parent::verify();

        if( stream_resolve_include_path( $this -> options -> value ) === FALSE ) {

            throw new InvalidArgumentException(
                'Argument is not a valid filepath'
            );
        }
    }

    // Prototypable Method Implementation

    /**
     * Prototypes a custom, and maybe complex, routine to one of
     * the available types by proxying, treating and handling
     * the mixed arguments received
     */
    public function prototype() {

        parent::prototype();

        /**
         * Gets path extension
         *
         * @return \Next\FileSystem\Path
         *  A new FileSystem Path Object with the extension of the input
         *  Data-type filepath if present and NULL otherwise
         */
        $this -> implement( $this, 'getExtension', function( $path ) {

            $extension = pathinfo( $path, PATHINFO_EXTENSION );

            return ( ! empty( $extension ) ? new Path( [ 'value' => $extension ] ) : NULL );

        }, $this -> _value );

        /**
         * Cleans boundary spaces, trailing slash and inverts
         * backslashes from given path
         *
         * @return \Next\FileSystem\Path
         *  A new FileSystem Path Object with the cleansed and fixed path
         */
        $this -> implement( $this, 'clean', function( $path ) {

            return new Path(
                [ 'value' => str_replace( '\\', '/', rtrim( trim( $path ), '/\\' ) ) ]
            );

        }, $this -> _value );

        // Custom Prototypes

        $this -> implement( $this, 'delete',      new Prototypes\Delete,      $this -> _value )
              -> implement( $this, 'copy',        new Prototypes\Copy,        $this -> _value )
              -> implement( $this, 'tree',        new Prototypes\Tree,        $this -> _value )
              -> implement( $this, 'classmapper', new Prototypes\ClassMapper, $this -> _value );
    }
}