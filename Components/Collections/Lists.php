<?php

/**
 * Collections Component Lists Class | Components\Collections\Lists.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Components\Collections;

use Next\Components\Object;              # Object Class

use Next\Components\Utils\ArrayUtils;    # ArrayUtils Class

use Next\Components\Debug\Exception;     # Exception Class

/**
 * Defines a structure derived from \Next\Components\Collections\AbstractCollection
 * through which a Collection can be accessed directly through their offsets
 *
 * @package    Next\Components\Collections
 */
class Lists extends AbstractCollection implements \ArrayAccess {

    /**
     * Get an Object from specified index, name or hash
     *
     * @param mixed|integer|string $reference
     *  Object reference to find
     *
     * @return \Next\Components\Object|boolean
     *  Object of given offset or FALSE if given offset doesn't exists
     *
     * @see \Next\Components\Collections\AbstractCollection::find()
     */
    public function item( $reference ) {

        $index = $this -> find( $reference );

        if( $index !== FALSE && $index != -1 && array_key_exists( $index, $this -> collection ) ) {
            return $this -> collection[ $index ];
        }

        return FALSE;
    }

    /**
     * Get Collection Neighbors
     *
     * @param  integer|optional $offset
     *  An Object offset within the Collection to serve as start point
     *
     * @param  integer|optional $limit
     *  A limit for how many neighbors before and after given offset
     *  will be retrieved
     *
     * @return array
     *  A slice of Object Collection with the elements
     *
     * @throws \OutOfRangeException
     *  Thrown if give offset is greater than the number of Objects
     *  in the Collection
     *
     * @todo Check the possibility of `$end` returns a negative value
     * @todo Replace the OutOfRangeException with internal Next Debug Component
     */
    public function getNeighbors( $offset = 0, $limit = 1 ) {

        if( $offset >= $this -> count() ) {
            throw new \OutOfRangeException( 'Requested offset exceeds the size of Collection' );
        }

        $start = ( $offset - $limit ) >= 0 ? ( $offset - $limit ) : 0;
        $end   = $offset - $start + $limit + 1;

        return array_slice( $this -> collection, $start, $end, TRUE );
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
    public function offsetExists( $offset ) {
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
     * as an Interface alias to AbstractCollection::add()
     *
     * @param mixed|string|integer $offset
     *  Offset where new data will be stored
     *
     * @param mixed|\Next\Components\Object $object
     *  Object to add
     *
     * @return void
     *
     * @throws \Next\Components\Debug\Exception
     *  Throw if given is not an instance of \Next\Components\Object
     *
     * @see \Next\Components\Collection\AbstractCollection::add()
     */
    public function offsetSet( $offset, $object ) {
        $this -> add( $object, $offset );
    }

    /**
     * Removes an Object from Collection at given offset
     * as an Interface alias to AbsractCollection::remove()
     *
     * @param mixed|string|integer $offset
     *  Offset to unset
     *
     * @return void
     *
     * @see \Next\Components\Collections\AbsractCollection::remove()
     */
    public function offsetUnset( $offset ) {
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
    protected function accept( Object $object ) {

        // Lists accepts everything

        return TRUE;
    }
}