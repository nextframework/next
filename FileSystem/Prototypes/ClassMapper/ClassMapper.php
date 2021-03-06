<?php

/**
 * ClassMapper Prototype Class: Tokens Identifier | FileSystem\Prototypes\ClassMapper\ClassMapper.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\FileSystem\Prototypes\ClassMapper;

/**
 * Iterates a \RecursiveIteratorIterator parsing all PHP files tokens
 * to find all classes and interfaces to build the classmap
 *
 * @package    Next\FileSystem
 *
 * @uses       FilterIterator
 *             RecursiveIteratorIterator
 *             SplFileInfo
 */
class ClassMapper extends \FilterIterator {

    /**
     * Class Map Generator Constructor
     *
     * @param \RecursiveIteratorIterator $iterator
     *  A \RecursiveIteratorIterator built with a
     *  \RecursiveDirectoryIterator over a path to traverse and
     *  filter the PHP Classes found
     *
     * @see \Next\FileSystem\Prototypes\ClassMapper::map()
     */
    public function __construct( \RecursiveIteratorIterator $iterator ) {

        parent::__construct( $iterator );

        // @see https://bugs.php.net/bug.php?id=52560

        $this -> rewind();
    }

    // FilterIterator Abstract Method Implementation

    /**
     * Check whether the current element of the iterator is acceptable
     *
     * For Class Mapping, filters defined RecursiveIterator elements to:
     * SplFileInfo Objects and real PHP Files only.
     *
     * Originally version authored by
     * Matthew Weier O'Phinney (http://goo.gl/86Zy)
     *
     * @return boolean
     *  TRUE if the current element is acceptable, otherwise FALSE
     */
    public function accept() : bool {

        $file = $this -> getInnerIterator() -> current();

        /**
         * @internal
         *
         * Filtering out everything that's not an instance of
         * \SplFileInfo and/or that's not a PHP File
         */
        if( ! $file instanceof \SplFileInfo ||
                ! $file -> isFile() ||
                    $file -> getBasename( '.php' ) == $file -> getBasename() ) {

            return FALSE;
        }

        // Parsing current PHP File Tokens

        $tokens   = token_get_all(
            file_get_contents( $file -> getRealPath() )
        );

        // Finding classes/interfaces

        $count    = count( $tokens );
        $i        = 0;

        while( $i < $count ) {

            $token = $tokens[ $i ];

            if( (array) $token !== $token ) {

                // Single character token found... Skip!

                $i += 1; // Faster than $i++

                continue;
            }

            list( $id, $content, $line ) = $token;

            switch( $id ) {

                case T_NAMESPACE:

                    // Namespace found; grab it for later

                    $namespace = '';

                    $done      = FALSE;

                    do {

                        ++$i;

                        $token = $tokens[ $i ];

                        if( is_string( $token ) ) {

                            if( ';' === $token ) {

                                $done = TRUE;
                            }

                            continue;
                        }

                        list( $type, $content, $line ) = $token;

                        switch( $type ) {

                            case T_STRING:
                            case T_NS_SEPARATOR:

                                $namespace .= $content;

                            break;
                        }

                    } while( ! $done && $i < $count );

                    // Set the namespace of this file in the object

                    $file -> namespace = $namespace;

                    break;

                case T_ABSTRACT:
                case T_CLASS:
                case T_INTERFACE:

                    // Get the basename of class (concrete or abstract) or interface

                    $class = '';

                    do {

                        ++$i;

                        $token = $tokens[ $i ];

                        if( is_string( $token ) ) {
                            continue;
                        }

                        list( $type, $content, $line ) = $token;

                        switch( $type ) {

                            case T_STRING:

                                $class = $content;

                            break;
                        }

                    } while( empty( $class ) && $i < $count );

                    // If something was found, let's flag it

                    if( ! empty( $class ) ) {

                        $file -> classname = $class;

                        return TRUE;
                    }

                    break;

                default: break;
            }

            ++$i;
        }

        // No class-type tokens found

        return FALSE;
    }
}