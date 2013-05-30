<?php

namespace Next\Components\Iterator;

use Next\Components\Object;    # Object Class

/**
 * Lists Class
 *
 * Lists are Collections with ability to find Objects from given offsets
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Lists extends AbstractCollection {

    /**
     * Get Object from specified Index
     *
     * @param integer $index
     *   Offset to retrieve
     *
     * @return Next\Components\Object|boolean
     *   Object of given offset or FALSE if given offset doesn't exists
     */
    public function get( $index ) {

        $index = (int) $index;

        if( array_key_exists( $index, $this -> collection ) ) {

            return $this -> collection -> offsetGet( $index );
        }

        return FALSE;
    }

    /**
     * Finds an Object
     *
     * Find an Object by smilarity between given string and
     * Object String Rpresentation
     *
     * @param string $param
     *   Object name (or any string) to find in Collection Lists
     *
     * @param integer|float|optional $level
     *   Minimum similarity level
     *
     * @return Next\Components\Object|boolean
     *   Object found, if found and FALSE otherwise
     */
    public function find( $param, $level = 90 ) {

        $mostProbable = array();

        foreach( $this as $object ) {

            similar_text(

                (string) $param,

                $object -> getClass() -> getShortName(), $percent
            );

            $mostProbable[] = array( $object, $percent );
        }

        // Sorting...

        usort(

            $mostProbable,

            function( $a, $b ) {

                return $a[ 1 ] < $b[ 1 ];
            }
        );

        // Filtering agains probability

        $mostProbable = array_filter(

            $mostProbable,

            function( $probable ) use( $level ) {

                return ( $probable[ 1 ] >= $level );
            }
        );

        // Returning the most probable Object

        return ( count( $mostProbable ) > 0 ? $mostProbable[ 0 ][ 0 ] : FALSE );
    }

    // Abstract Methods Implementation

    /**
     * Check Object acceptance
     *
     * @param Next\Components\Object $object
     *   Object to have its acceptance in Collection checked
     *
     * @return boolean
     *   Always TRUE, because a Collection Lists accepts everything
     */
    protected function accept( Object $object ) {

        // Lists accepts everything

        return TRUE;
    }
}
