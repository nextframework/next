<?php

/**
 * Array Utils Class | Components\Utils\ArrayUtils.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Utils;

/**
 * Collection of Array utility routines not present in original
 * language or improved and/or expanded versions of them
 *
 * @package    Next\Components\Utils
 *
 * @uses       RecursiveArrayIterator
 *             RecursiveIteratorIterator
 *             ReflectionObject
 *             stdClass
 */
class ArrayUtils {

    /**
     * Flattens a multidimensional array, making it unidimensional
     *
     * @param array $array
     *  Array to flatten
     *
     * @return array
     *  The multidimensional array provided flattened in one single dimension
     *
     * @see https://stackoverflow.com/a/8611365/5613506
     *  Original author
     */
    public static function flatten( array $array ) : array {
        return array_reduce( $array, 'array_merge', array() );
    }

    /**
     * Equalize array lengths
     *
     * The shorter array will receive as many NULL elements as needed to have the same length
     * of the larger array.
     *
     * Both arguments are optional, so this method can be used to create dummy arrays based upon
     * other array length
     *
     * And both arguments must be passed as reference, so the changes can be applied
     *
     * @param array|optional $a
     *  First Array
     *
     * @param array|optional $b
     *  Second Array
     */
    public static function equalize( array &$a = [], array &$b = [] ) : void {

        $l1 = count( $a );
        $l2 = count( $b );

        if( $l1 == $l2 ) return;

        if( $l1 > $l2 ) {

            $b = array_merge( $b, array_fill( 0, ( $l1 - $l2 ), NULL ) );

        } else {

            $a = array_merge( $a, array_fill( 0, ( $l2 - $l1 ), NULL ) );
        }
    }

    /**
     * Searches value inside a multidimensional array, returning its index
     *
     * @param mixed|array $haystack
     *  The haystack to search
     *
     * @param mixed $needle
     *  The needle we are looking for
     *
     * @param mixed|optional $index
     *  Allow to define a specific index where the data will be searched
     *
     * @param boolean|optional $strict
     *  Deactivates case strictness when comparing.
     *  This is available only is `$needle` is of a scalar
     *  type (i.e. integer, float, string or boolean)
     *  Defaults to TRUE, which means that, by default, the routine is
     *  case-sensitive
     *
     * @return integer|string
     *  If given needle can be found in given haystack, its index will
     *  be returned. Otherwise, -1 will
     *
     * If provided haystack is NULL or empty, -1 will be returned as well
     *
     * @see http://www.php.net/manual/en/function.array-search.php#97645
     *  Original version without checkings and strictness
     *  authored by "giulio provasi"
     */
    public static function search( $haystack, $needle, $index = NULL, $strict = TRUE ) {

        if( $haystack === NULL || count( $haystack ) == 0 ) return -1;

        $arrayIterator = new \RecursiveArrayIterator( $haystack );

        $iterator = new \RecursiveIteratorIterator( $arrayIterator );

        while( $iterator -> valid() ) {

            if( $strict !== FALSE ) {

                if( ( ( isset( $index )     AND ( $iterator -> key() == $index ) ) OR
                    ( ! isset( $index ) ) ) AND ( $iterator -> current() == $needle ) ) {

                    return $arrayIterator -> key();
                }

            } else {

                if( is_scalar( $needle ) ) {

                    if( ( ( isset( $index )     AND ( strcasecmp( $iterator -> key(), $index ) == 0 ) ) OR
                        ( ! isset( $index ) ) ) AND ( strcasecmp( $iterator -> current(), $needle ) == 0 ) ) {

                        return $arrayIterator -> key();
                    }
                }
            }

            $iterator -> next();
        }

        return -1;
    }

    /**
     * Map given (stdClass) Object to array, recursively
     *
     * @param mixed $param
     *  Object to be mapped
     *
     * @return array
     *  Given object map into array
     */
    public static function map( $param ) {

        if( (array) $param === $param || $param instanceof \stdClass ) {
            return array_map( __METHOD__, (array) $param );
        }

        if( is_object( $param ) ) {

            /**
             * @internal
             *
             * Saving object instance to use it later when retrieving properties
             * without being overwritten when array_map() pass over
             */
            $object = $param;

            $reflector = new \ReflectionObject( $object );

            $properties = $reflector -> getProperties(
                \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED
            );

            $param = [];

            foreach( $properties as $property ) {

                // Ignoring Framework's Properties

                if( preg_match( '/^Next\\\\*/', $property -> class ) != 0 ) continue;

                $property -> setAccessible( TRUE );

                $param[ $property -> getName() ] = $property -> getValue( $object );
            }
        }

        return $param;
    }

    /**
     * Adjoin Multidimensional Arrays
     *
     * Differently of the native array_merge_recursive function,
     * this one merges the arrays but overwrites the indexes of first
     * arrays which are present in second, keeping the same structure
     * of arrays you enter.
     *
     * @param array $a
     *  Initial array
     *
     * @param array $b
     *  Array to unite
     *
     * @return array
     *  A new array with elements in <strong>$b</strong> united to elements in <strong>$a</strong>
     *
     * @see http://www.phpbuilder.com/board/showpost.php?p=10886411&postcount=6
     *  Original source authored by "bpat1434"
     */
    public static function union( array $a, array $b ) : array {

        foreach( $b as $index => $value ) {

            if( isset( $a[ $index ] ) ) {

                if( (array) $value === $value ) {

                    $a[ $index ] = self::union( $a[ $index ], $value );

                } else {

                    $a[ $index ] = $value;
                }

            } else {

                $a[ $index ] = $value;
            }
        }

        return $a;
    }

    /**
     * Insert an array inside other pushing existent indexes
     * without overwrite any value
     *
     * @param array $a
     *  Array to insert
     *
     * @param array $b
     *  Array where new data will be inserted
     */
    public static function insert( array $a, array &$b ) : void {

        foreach( $a as $offset => $value ) {

            array_splice(

                $b, $offset, count( $b ),

                array_merge( [ $value ], array_slice( $b, $offset ) )
            );
        }
    }

    /**
     * Clean an array
     *
     * Allow all the empty dimensions (if any) of resulting array to be
     * destructed, by removing its index.
     *
     * Also allow the resulting array to be reindexed.
     *
     * @param array $array
     *  The array to be cleaned
     *
     * @param boolean|optional $allowZeros
     *  Defines whether or not if a 'zero' will be considered as NULL
     *
     * @param boolean|optional $recursive
     *  Defines whether or not if the cleanup will be applied recursively
     *
     * @param boolean|optional $destruct
     *  Defines whether or not if an empty dimension will be removed
     *
     * @param boolean|optional $reindex
     *  Defines whether ot not if the resulting array will be reindexed
     *
     * @return array
     *  Input array, now cleaned
     */
    public static function clean( array $array, $allowZeros = TRUE, $recursive = FALSE,
                                  $destruct = FALSE, $reindex = FALSE ) : array {

        foreach( $array as $index => $value ) {

            if( (array) $array[ $index ] === $array[ $index ] && $recursive ) {
                $array[ $index ] = self::clean( $array[ $index ], $allowZeros, $recursive, $destruct, $reindex );
            }

            if( empty( $value ) && ( ! $allowZeros || ( $allowZeros !== FALSE && $value != '0' ) ) ) {
                unset( $array[ $index ] );
            }
        }

        // Destructing empty dimensions

        if( $destruct ) {

            $array = array_filter( $array, function( $value ) use( $allowZeros ) : bool {
               return ( ! ( empty( $value ) && ( ! $allowZeros || ( $allowZeros !== FALSE && $value != '0' ) ) ) );
            });
        }

        // Reindexing

        if( $reindex ) $array = array_values( $array );

        return $array;
    }

    /**
     * Get last key
     *
     * @param array $stack
     *  Given array
     *
     * @return string|integer
     *  Last index of given array
     */
    public static function lastKey( array $stack ) {
        return key( array_slice( $stack, -1, 1, TRUE ) );
    }

    /**
     * Transpose a multidimensional array
     *
     * @param array $array
     *   Multidimensional array to transpose
     *
     * @return array
     *  Transpose multidimensional array
     *
     * @see http://stackoverflow.com/a/3423692/753531
     *  Original source authored by "Codler"
     */
    public static function transpose( array $array ) : array {

        array_unshift( $array, NULL );

        return call_user_func_array( 'array_map', $array );
    }

    /**
     * Filters input array entries equal to FALSE but, differently from default
     * behavior of native array_filter() function, if defined, zeros will not be
     * considered as FALSE, and thus they'll not be removed from given array
     *
     * Defaults to TRUE, zeros are allowed
     *
     * @param array $array
     *  Array to filter
     *
     * @param boolean|optional $allowZeros
     *  Define whether or not zeros will be allowed in filtered array. If FALSE,
     *  array_filter() with its default behavior will be used. Otherwise, we'll use
     *  strlen() as callback.
     *
     * Defaults to TRUE, zeros are allowed.
     *
     * @return array
     *  Input array filtered
     *
     * @see http://php.net/manual/en/function.array-filter.php#111091
     */
    public static function filter( array $array, $allowZeros = TRUE ) : array {

        return ( ( $allowZeros !== FALSE ) ?
                      array_filter( $array, 'strlen' ) : array_filter( $array ) );
    }
}
