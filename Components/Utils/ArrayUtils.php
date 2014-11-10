<?php

namespace Next\Components\Utils;

/**
 * Arrays Utils Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class ArrayUtils {

    /**
     * Equalize array lengths
     *
     * The shorter array will receive as many NULL elements as needed to have the same length
     * of th larger array.
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
    public static function equalize( array &$a = array(), array &$b = array() ) {

        $l1 = count( $a );
        $l2 = count( $b );

        if( $l1 == $l2 ) {
            return;
        }

        if( $l1 > $l2 ) {

            $b = array_merge( $b, array_fill( 0, ( $l1 - $l2 ), NULL ) );

        } else {

            $a = array_merge( $a, array_fill( 0, ( $l2 - $l1 ), NULL ) );
        }
    }

    /**
     * Searches value inside a multidimensional array, returning its index
     *
     * Original function by "giulio provasi" (link below)
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
     * @return integer|string
     *  If given needle can be found in given haystack, its index will
     *  be returned. Otherwise, -1 will
     *
     * @see http://www.php.net/manual/en/function.array-search.php#97645
     */
    public static function search( $haystack, $needle, $index = NULL ) {

        if( is_null( $haystack ) ) {
            return -1;
        }

        $arrayIterator = new \RecursiveArrayIterator( $haystack );

        $iterator = new \RecursiveIteratorIterator( $arrayIterator );

        while( $iterator -> valid() ) {

            if( ( ( isset( $index ) and ( $iterator -> key() == $index ) ) or
                ( ! isset( $index ) ) ) and ( $iterator -> current() == $needle ) ) {

                return $arrayIterator -> key();
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

        if( is_object( $param ) ) {
            $param = get_object_vars( $param );
        }

        if( is_array( $param ) ) {
            return array_map( __METHOD__, $param );
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
     * Original function by "bpat1434" (link below)
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
     */
    public static function union( array $a, array $b ) {

        foreach( $b as $index => $value ) {

            if( isset( $a[ $index ] ) ) {

                if( is_array( $value ) ) {

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
    public static function insert( array $a, array &$b ) {

        foreach( $a as $offset => $value ) {

            array_splice(

                $b, $offset, count( $b ),

                array_merge(

                    array( $value ),

                    array_slice( $b, $offset )
                )
            );
        }
    }

    /**
     * Check if a value exists in array
     *
     * @param mixed $needle
     *  The needle we are looking for
     *
     * @param array $haystack
     *  The haystack to search
     *
     * @param boolean|optional $strict
     *  Different of native in_array(), the strict flag here distinguishes
     *  two different strings and not only two different types of data
     *
     * @return boolean
     *  TRUE if given needle is present in given haystack, even if
     *  multidimensional. FALSE otherwise.
     */
    public static function in( $needle, array $haystack, $strict = FALSE ) {

        $iterator = new \RecursiveIteratorIterator( new \RecursiveArrayIterator( $haystack ) );

        foreach( $iterator as $item ) {

            if( ! $strict ) {

                // Strings

                if( is_string( $item ) && is_string( $needle ) ) {

                    return ( strcasecmp( $item, $needle ) == 0 );

                } elseif( is_object( $item ) && method_exists( $item, '__toString' ) ) {

                    // Objects

                    return ( strcasecmp( (string) $item, $needle ) == 0 );

                } elseif( is_array( $item ) ) {

                    // Arrays

                    return ( serialize( $item ) == serialize( $needle ) );

                } else {

                    // Everything else

                    return( $item == $needle );
                }

            } else {

                return ( $item === $needle );
            }
        }

        return FALSE;
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
                                  $destruct = FALSE, $reindex = FALSE ) {

        foreach( $array as $index => $value ) {

            if( is_array( $array[ $index ] ) && $recursive ) {

                $array[ $index ] = self::clean( $array[ $index ], $recursive, $destruct, $reindex );

            } else {

                if( $allowZeros ) {

                    if( empty( $value ) && $value != '0' ) {

                        unset( $array[ $index ] );
                    }

                } else {

                    if( empty( $value ) ) {

                        unset( $array[ $index ] );
                    }
                }
            }
        }

        // Should we reindex?

        if( $reindex ) {
            $array = array_values( $array );
        }

        // Should we destroy empty dimensions?

        if( $destruct ) {
            $array = array_filter( $array );
        }

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
     * @param  array $array
     *   Multidimensional array to transpose
     *
     * @return array
     *  Transpose multidimensional array
     *
     * @see http://stackoverflow.com/a/3423692/753531
     */
    public static function transpose( array $array ) {

        array_unshift( $array, NULL );

        return call_user_func_array( 'array_map', $array );
    }
}
