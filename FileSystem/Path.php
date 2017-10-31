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

use Next\Components\Types\Strings;    # Strings Data-type Class

/**
 * The Path Data-type Type with prototypes of external/custom resources related
 * to FileSystem operations
 *
 * @package    Next\FileSystem
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Components\Types\Strings
 *             Next\FileSystem\Prototypes\Delete
 *             Next\FileSystem\Prototypes\Copy
 *             Next\FileSystem\Prototypes\Tree
 *             Next\FileSystem\Prototypes\ClassMapper
 */
class Path extends Strings {

    // Prototypable Method Implementation

    /**
     * Prototypes a custom, and maybe complex, routine to one of
     * the available types by proxying, treating and handling
     * the mixed arguments received
     */
    public function prototype() {

        parent::prototype();

        $this -> implement( $this, 'getExtension', [ $this, 'getExtension' ], $this -> _value );
        $this -> implement( $this, 'clean',        [ $this, 'clean' ],        $this -> _value );

        // Custom Prototypes

        $this -> implement( $this, 'delete',      'Next\FileSystem\Prototypes\Delete',      $this -> _value )
              -> implement( $this, 'copy',        'Next\FileSystem\Prototypes\Copy',        $this -> _value )
              -> implement( $this, 'tree',        'Next\FileSystem\Prototypes\Tree',        $this -> _value )
              -> implement( $this, 'classmapper', 'Next\FileSystem\Prototypes\ClassMapper', $this -> _value );
    }

    // Custom/Adapted Prototypes

    /**
     * Gets path extension
     *
     * @param string $path
     *  Path to have its extension retrieved from
     *
     * @return \Next\FileSystem\Path|NULL
     *  A new FileSystem Path Object with the extension of the input
     *  Data-type filepath if present and NULL otherwise
     */
    protected function getExtension( string $path ) :? Path {

        $extension = pathinfo( $path, PATHINFO_EXTENSION );

        return ( ! empty( $extension ) ? new Path( [ 'value' => $extension ] ) : NULL );
    }

    /**
     * Cleans boundary spaces, trailing slash and inverts
     * backslashes from given path
     *
     * @param string $path
     *  Path string to clean
     *
     * @return \Next\FileSystem\Path
     *  A new FileSystem Path Object with the cleansed and fixed path
     */
    protected function clean( string $path ) : Path {

        return new Path(
            [ 'value' => strtr( rtrim( trim( $path ), '/\\' ), [ '\\' => '/' ] ) ]
        );
    }
}