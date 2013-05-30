<?php

namespace Next\Tools;

use Next\Tools\ClassMapper\ClassMapperException;          # ClassMapper Exception Class
use Next\Components\Utils\ArrayUtils;                     # Array Utils Class

/**
 * Class Mapper Tool
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ClassMapper extends \FilterIterator {

    /**
     * Available Output Classes
     *
     * @var array $available
     */
    private $available = array( 'Standard', 'XML' );

    /**
     * Class Map Generator Constructor
     *
     * @param DirectoryIterator|RecursiveIterator|string $param
     *   Directory to iterate or a well formed Recursive Iterator
     *
     * @throws ClassMapperException
     *   Given argument is a string but do not refers to avalid directory
     *
     * @throws ClassMapperException
     *   Given argument is not a string pointing to a valid Directory
     *   nor is a DirectoryIterator Object
     *
     * @see https://bugs.php.net/bug.php?id=52560
     */
    public function __construct( $param ) {

        if( is_string( $param ) ) {

            if( ! is_dir( $param ) ) {

                throw ClassMapperException::wrongUse(

                    'ClassMapper expect a valid directory name'
                );
            }

            $param = new \RecursiveDirectoryIterator( $param );
        }

        if( ! $param instanceof \DirectoryIterator ) {

            throw ClassMapperException::wrongUse(

                'Classmapper expect a DirectoryIterator'
            );
        }

        if( $param instanceof \RecursiveIterator ) {

            $iterator = new \RecursiveIteratorIterator( $param );

        } else {

            $iterator = $param;
        }

        parent::__construct( $iterator );

        $this -> rewind();
    }

    /**
     * Build the Class Map
     *
     * @param string $format
     *   Output Format from Available Formats
     *
     * @param mixed|optional $options
     *   List of Options to affect Database Drivers. Acceptable values are:
     *
     *   <p>
     *
     *       <ul>
     *
     *           <li>Associative and multidimensional array</li>
     *
     *           <li>
     *
     *               An {@link http://php.net/manual/en/reserved.classes.php stdClass Object}
     *
     *           </li>
     *
     *           <li>A well formed Next\Components\Parameter Object</li>
     *
     *       </ul>
     *
     *   </p>
     *
     *   <p>There are no Common Options defined so far.</p>
     *
     *   <p>
     *       All the arguments taken in consideration are defined in
     *       and by factored classes
     *   </p>
     *
     * @see Next\Components\Parameter
     *
     * @return mixed|void
     *   If chosen format allow the result to be returned, it will
     *   accordingly to the its rules
     *
     *   Otherwise, nothing is returned
     *
     * @throws Next\Tools\ClassMapper\ClassMapperException
     *   Invalid or unsupported Mapping Format
     */
    public function build( $format, $options = NULL ) {

        if( ! in_array( (string) $format, $this -> available ) ) {
            throw ClassMapperException::unknown();
        }

        // Building Definitions

        $map = new \stdClass;

        $iterator =& $this;

        iterator_apply(

            $iterator,

            function() use ( $iterator, $map ) {

                $file = $iterator -> current();

                $namespace = '';

                if( ! empty( $file -> namespace ) ) {

                    $namespace = sprintf( '%s\\', $file -> namespace );
                }

                $classname = $namespace . $file -> classname;

                $map -> {$classname} = $file -> getRealPath();

                return TRUE;
            }
        );

        // Building Output Format Classname

        $class = sprintf( 'Next\Tools\ClassMapper\%s', (string) $format );

        // Instantiating Object

        $instance = new $class( $options );

        // Building!

        return $instance -> build( ArrayUtils::map( $map ) );
    }

    // FilterIterator Abstract Method Implementation

    /**
     * Check whether the current element of the iterator is acceptable
     *
     * For Class Mapping, filters defined RecursiveIterator elements to:
     * SplFileInfo Objects and real PHP Files only.
     *
     * Authored by Matthew Weier O'Phinney (http://goo.gl/86Zy)
     *
     * @return boolean
     *   TRUE if the current element is acceptable, otherwise FALSE
     */
    public function accept() {

        $file = $this -> getInnerIterator() -> current();

        // Only SplFileInfo Objects, please

        if( ! $file instanceof \SplFileInfo ) {
            return FALSE;
        }

        // Only real files, ok?

        if( ! $file -> isFile() ) {
            return false;
        }

        // And only PHP files, copy?

        if( $file -> getBasename( '.php' ) == $file -> getBasename() ) {
            return false;
        }

        // Reading File Contents

        $contents = file_get_contents( $file -> getRealPath() );

        // Parsing its tokens

        $tokens   = token_get_all( $contents );

        // Finding classes/interfaces

        $count    = count( $tokens );
        $i        = 0;

        while( $i < $count ) {

            $token = $tokens[ $i ];

            if( ! is_array( $token ) ) {

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