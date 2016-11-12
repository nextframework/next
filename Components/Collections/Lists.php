<?php

namespace Next\Components\Collections;

use Next\Components\Object;              # Object Class

use Next\Components\Utils\ArrayUtils;    # ArrayUtils Class

use Next\Components\Debug\Exception;     # Exception Class

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
class Lists extends AbstractCollection implements \ArrayAccess {

    /**
     * Get an Object from specified index, name or hash
     *
     * @param mixed|integer|string $reference
     *  Object reference to find
     *
     * @return Next\Components\Object|boolean
     *  Object of given offset or FALSE if given offset doesn't exists
     *
     * @see Next\Components\Collections\AbstractCollection::find()
     */
    public function item( $reference ) {

        $index = $this -> find( $reference );

        if( $index !== FALSE && $index != -1 && array_key_exists( $index, $this -> collection ) ) {

            return $this -> collection[ $index ];
        }

        return FALSE;
    }

    // ArrayAccess Interface Methods Implementation

    /**
     * Checks whether or not an offset exists within the Collection
     * as Interface alias to Lists::item()
     *
     * @param  mixed|string|integer $offset
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
     * @param  mixed|string|integer $offset
     *  Offset to retrieve data from
     *
     * @return Next\Components\Object|boolean
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
     * @param  mixed|string|integer $offset
     *  Offset where new data will be stored
     *
     * @param mixed|Next\Components\Object $object
     *  Object to add
     *
     * @return void
     *
     * @throws Next\Components\Debug\Exception
     *  Throw if given is not an instance of Next\Components\Object
     *
     * @see Next\Components\Collection\AbstractCollection::add()
     */
    public function offsetSet( $offset, $object ) {
        $this -> add( $object, $offset );
    }

    /**
     * Removes an Object from Collection at given offset
     * as an Interface alias to AbsractCollection::remove()
     *
     * @param  mixed|string|integer $offset
     *  Offset to unset
     *
     * @return void
     *
     * @see Next\Components\Collections\AbsractCollection::remove()
     */
    public function offsetUnset( $offset ) {
        $this -> remove( $offset );
    }

    // Abstract Methods Implementation

    /**
     * Check Object acceptance
     *
     * @param Next\Components\Object $object
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