<?php

/**
 * Collections Component Lists Class | Components\Collections\Lists.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Collections;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\OutOfRangeException;

use Next\Components\Object;              # Object Class
use Next\Components\Utils\ArrayUtils;    # ArrayUtils Class

/**
 * A variation of of Objects' Collection with always sequential indexes through
 * which Objects can be accessed directly by offsets
 *
 * @package    Next\Components\Collections
 *
 * @uses       Next\Exception\Exceptions\OutOfRangeException
 *             Next\Components\Object
 *             Next\Components\Utils\ArrayUtils
 *             ArrayAccess
 */
class Lists extends Collection implements \ArrayAccess {

    /**
     * Get an Object from specified index, name or hash
     *
     * @param mixed|integer|string $reference
     *  Object reference to find
     *
     * @return \Next\Components\Object|integer
     *  Object at given offset or -1 if unable to find it within
     *  the Collection
     *
     * @see \Next\Components\Collections\Collection::find()
     */
    public function item( $reference ) {

        $index = $this -> find( $reference );

        return ( $index != -1 ) ? $this -> collection[ $index ] : -1;
    }

    /**
     * Get Collection Neighbours
     *
     * @param  integer|optional $offset
     *  An Object offset within the Collection to serve as start point
     *
     * @param  integer|optional $limit
     *  A limit for how many neighbours before and after given offset
     *  will be retrieved
     *
     * @return array
     *  A slice of Object Collection with the elements
     *
     * @throws Next\Exception\Exceptions\OutOfRangeException
     *  Thrown if give offset is greater than the number of Objects
     *  in the Collection
     *
     * @todo Check the possibility of `$end` returns a negative value
     */
    public function getNeighbours( $offset = 0, $limit = 1 ) : Lists {

        if( $offset > count( $this ) ) {

            throw new OutOfRangeException(
                'Requested offset exceeds the size of Collection'
            );
        }

        $start = ( $offset - $limit ) >= 0 ? ( $offset - $limit ) : 0;
        $end   = $offset - $start + $limit + 1;

        return new Lists(
            [ 'collection' => array_slice( $this -> collection, $start, $end, TRUE ) ]
        );
    }

    // ArrayAccess Interface Methods Implementation

    /**
     * Checks whether or not an offset exists within the Collection
     * as Interface alias to Lists::item()
     *
     * @param mixed|string|integer $offset
     *  Offset to search
     *
     * @return boolean
     *  TRUE if given offset exists and FALSE otherwise
     *
     * @see Lists::item()
     */
    public function offsetExists( $offset ) : bool {
        return ( $this -> item( $offset ) !== FALSE );
    }

    /**
     * Returns the Object stored at given offset in the Collection
     * as an Interface alias to Lists::item()
     *
     * @param mixed|string|integer $offset
     *  Offset to retrieve data from
     *
     * @return \Next\Components\Object|boolean
     *  Object stored if given offset exists and FALSE otherwise
     *
     * @see Lists::item()
     */
    public function offsetGet( $offset ) {
        return $this -> item( $offset );
    }

    /**
     * Assign a value to the specified offset in the Collection
     * as an Interface alias to Collection::add()
     *
     * @param mixed|string|integer $offset
     *  Offset where new data will be stored
     *
     * @param \Next\Components\Object $object
     *  Object to add
     *
     * @see \Next\Components\Collection\Collection::add()
     */
    public function offsetSet( $offset, $object ) : void {
        $this -> add( $object, $offset );
    }

    /**
     * Removes an Object from Collection at given offset
     * as an Interface alias to AbsractCollection::remove()
     *
     * @param mixed|string|integer $offset
     *  Offset to unset
     *
     * @see \Next\Components\Collections\AbsractCollection::remove()
     */
    public function offsetUnset( $offset ) : void {
        $this -> remove( $offset );
    }

    // Abstract Methods Implementation

    /**
     * Check Object acceptance
     *
     * @param \Next\Components\Object $object
     *  Object to have its acceptance in Collection checked
     *
     * @return boolean
     *  Always TRUE, because a Collection Lists accepts everything
     */
    protected function accept( Object $object ) : bool {

        // Lists accepts everything

        return TRUE;
    }
}