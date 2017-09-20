<?php

namespace Next\FileSystem;

/**
 * InvalidArgumentException Class
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Types\String;

class Path extends String {

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

        $this -> implement( $this, 'delete', new Prototypes\Delete, $this -> _value )
              -> implement( $this, 'copy',   new Prototypes\Copy,   $this -> _value );
    }
}