<?php

/**
 * Collections Component Abstract Class | Components\Collection\AbstractCollection.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Components\Collections;

use Next\Components\Object;              # Object Class

use Next\Components\Utils\ArrayUtils;    # ArrayUtils Class

/**
 * Defines the base structure for an Object Collections created with Next Framework
 *
 * @package    Next\Components\Collections
 */
abstract class AbstractCollection extends Object
    implements \Countable, \IteratorAggregate, \Serializable {

    /**
     * Collection Storage
     *
     * @var ArrayObject $collection
     */
    protected $collection;

    /**
     * References Table (name, hashes...)
     *
     * @var array $references
     */
    protected $references;

    /**
     * Iterator Class
     *
     * @var Iterator $iterator
     */
    private $iterator;

    /**
     * Additional Initialization
     *
     * @see \Next\Components\Collection\AbstractCollection::clear()
     */
    public function init() {
        $this -> clear();
    }

    /**
     * Clear the Collection
     *
     * <ul>
     *
     *     <li>Empties Collection Storage and References Storage</li>
     *     <li>Redefine Collection Iterator</li>
     *
     * </ul>
     *
     * @return \Next\Components\Collection\AbstractCollection
     *  Collection Object (Fluent Interface)
     */
    public function clear() {

        $this -> collection = array();

        $this -> references = array();

        $this -> iterator   = 'ArrayIterator';

        return $this;
    }

    // Collection's Manipulation Methods

    /**
     * Adds a new Object to Collection
     *
     * @param \Next\Components\Object $object
     *  Object to add to Collection
     *
     * @param mixed|integer|optional
     *  Specific offset to add the Object to
     *
     * @return \Next\Components\Collection\AbstractCollection
     *  Collection Object (Fluent Interface)
     */
    public function add( Object $object, $offset = NULL ) {

        // Check Object acceptance

        if( ! $this -> accept( $object ) ) {
            return $this;
        }

        // Adding Object and References at specific offset

        if( $offset !== NULL ) {

            $this -> collection[ $offset ] = $object;

            $this -> references[ $offset ] = array(

                'name' => (string) $object,
                'hash' => $object -> getHash()
            );

        } else {

            // Or at the end of the Collection

            $this -> collection[] = $object;

            $this -> references[] = array(

                'name' => (string) $object,
                'hash' => $object -> getHash()
            );
        }

        return $this;
    }

    /**
     * Adds a new Object to the beginning of Collection
     *
     * @param \Next\Components\Object $object
     *  Object to prepend to Collection
     *
     * @return \Next\Components\Collection\AbstractCollection
     *  Collection Object (Fluent Interface)
     */
    public function prepend( Object $object ) {

        // Checking Object acceptance

        if( ! $this -> accept( $object ) ) {
            return $this;
        }

        array_unshift( $this -> collection, $object );

        array_unshift(

            $this -> references,

            array(
                'name' => (string) $object,
                'hash' => $object -> getHash()
            )
        );

        return $this;
    }

    /**
     * Removes an Object from Collection
     *
     * @param mixed|integer|string|\Next\Components\Object $reference
     *  Offset to remove
     *
     * @return \Next\Components\Collection\AbstractCollection
     *  Collection Object (Fluent Interface)
     */
    public function remove( $reference ) {

        $i = $this -> find( $reference );

        if( $i !== FALSE && $i != -1 && array_key_exists( $i, $this -> collection ) ) {
            unset( $this -> collection[ $i ], $this -> references[ $i ] );
        }

        return $this;
    }

    /**
     * Check if an Objects exists
     *
     * @param mixed|integer|string|\Next\Components\Object $reference
     *  An Object object or the name of an Object to check if
     *  it's present in Collection
     *
     * @return boolean
     *  TRUE if given Object is already present in Collection and FALSE otherwise
     */
    public function contains( $reference ) {

        /**
         * @internal
         * Differently of Lists::item() that accepts integers as
         * possible reference (direct offset), here, such type is prohibited
         * as we're testing if an Object (or a reference to it) exists in the
         * Collection, instead of trying to find one for manipulation
         */
        if( is_int( $reference ) ) return -1;

        return ( $this -> find( $reference ) != -1 );
    }

    /**
     * Shuffles Collection while keeping the References Table relation
     *
     * @return \Next\Components\Collection\AbstractCollection|void
     *  Collection Object (Fluent Interface), if Collection has elements
     */
    public function shuffle() {

        // Nothing to shuffle

        if( empty( $this -> collection ) ) return;

        $order = range( 1, count( $this -> collection ) );

        shuffle( $order );

        array_multisort( $order, $this -> collection, $this -> references );

        return $this;
    }

    /**
     * Shifts the first element off the Collection, regardless
     * of its index within it, therefore it's not affected by
     * AbstractCollection::shuffle(), for example
     *
     * @param boolean $shift
     *  If TRUE the first element will be *really* removed from the
     *  Collection before being returned, along with its metadata.
     *
     *  If FALSE (default) it will be just retrieved, without
     *  affecting Collection's data
     *
     * @return \Next\Components\Object
     *  First Object added to Collection, if Collection has any elements
     *  Or the Collection Object as of Fluent Interface, if Collection is empty
     *
     * @see \Next\Components\Collections\AbstractCollection::shuffle()
     * @see \Next\Components\Collections\AbstractCollection::remove()
     */
    public function shift( $shift = FALSE ) {

        // Nothing to shift

        if( empty( $this -> collection ) ) return $this;

        reset( $this -> collection );
        reset( $this -> references );

        $object = current( $this -> collection );

        if( $shift !== FALSE ) {
            $this -> remove( $object );
        }

        return $object;
    }

    /**
     * Pops the last element off the Collection, regardless
     * of its index within it, therefore it's not affected by
     * AbstractCollection::shuffle(), for example
     *
     * @param boolean $pop
     *  If TRUE the last element will be *really* removed from the
     *  Collection before being returned, along with its metadata.
     *
     *  If FALSE (default) it will be just retrieved, without
     *  affecting Collection's data
     *
     * @return \Next\Components\Object
     *  Last Object added to Collection, if Collection has any elements
     *  Or the Collection Object as of Fluent Interface, if Collection is empty
     *
     * @see \Next\Components\Collections\AbstractCollection::shuffle()
     * @see \Next\Components\Collections\AbstractCollection::remove()
     */
    public function pop( $pop = FALSE ) {

        // Nothing to pop

        if( empty( $this -> collection ) ) return $this;

        end( $this -> collection );
        end( $this -> references );

        $offset = key( $this -> collection );

        $object = $this -> collection[ $offset ];

        if( $pop !== FALSE ) {
            $this -> remove( $offset );
        }

        return $object;
    }

    /**
     * Finds an Object offset inside the Collection
     *
     * @param mixed|string|integer|\Next\Components\Object $reference
     *  A reference to search for.
     *  It can be a string, an integer or an \Next\Components\Object object
     *
     * @return boolean|integer
     *  Returns -1 if Collection is empty or if the reference couldn't be
     *  found within it and FALSE if the searching process fails
     *
     * @see \Next\Components\Utils\ArrayUtils::search()
     */
    public function find( $reference ) {

        if( empty( $this -> collection ) ) return -1;

        // If an Object object is informed, let's search by hash

        if( $reference instanceof Object ) {

            $hash = $reference -> getHash();

            if( ( $offset = ArrayUtils::search( $this -> references, $hash, 'hash' ) ) !== -1 ) {
                return $offset;
            }

            return  -1;
        }

        /**
         * @internal
         * If an integer (not a numeric string) greater than or equal
         * to zero is informed, let's use it "as is"
         */
        if( is_int( $reference ) && $reference >= 0 ) return $reference;

        // Otherwise, let's search by name

        return ArrayUtils::search(
            $this -> references, (string) $reference, 'name'
        );
    }

    // Accessors

    /**
     * Checks whether or not the Collection is empty
     * as an Interface alias for Countable::count()
     *
     * @return boolean
     *  TRUE if Collection is empty and FALSE otherwise
     */
    public function isEmpty() {
        return count( $this -> collection == 0 );
    }

    /**
     * Get Collection Storage
     *
     * @return ArrayObject
     *  Collection Object
     */
    public function getCollection() {
        return $this -> collection;
    }

    /**
     * Get Collection Metadata
     * Mostly for debugging purposes only
     *
     * @return array
     *  Collection's Metadata
     */
    public function getReferences() {
        return $this -> references;
    }

    // Abstract Methods Definition

    /**
     * Check Object acceptance
     *
     * @param \Next\Components\Object $object
     *  Object to have its acceptance in Collection checked
     */
    abstract protected function accept( Object $object );

    // Countable Interface Method Implementation

    /**
     * Count elements in Collection
     *
     * @return integer
     *  The number of Objects present in Collection
     */
    public function count() {
        return count( $this -> collection );
    }

    // IteratorAggregate Interface Method

    /**
     * Set an External Iterator
     *
     * @param string|Iterator $iterator
     *  A valid Iterator or a classname string of a valid Iterator
     *
     * @return \Next\Components\Collection\AbstractCollection
     *  Collection Object (Fluent Interface)
     */
    public function setIterator( $iterator ) {

        $this -> iterator = $iterator;

        return $this;
    }

    /**
     * Get External Iterator
     *
     * @return Iterator|ArrayIterator
     *  An Object implementing Iterator Interface or an ArrayIterator,
     *  if no valid Iterator was provided
     */
    public function getIterator() {

        // Custom External Iterator

        if( $this -> iterator instanceof \Iterator ) {
            return $this -> iterator;
        }

        // If a classname was defined, we have to create an object from it

        $it = new $this -> iterator( $this -> collection );

        // Using default Iterator if a no valid one was provided

        return ( $it instanceof \Iterator ? $it : new \ArrayIterator( $this -> collection ) );
    }

    // Serializable Interface Method Implementation

    /**
     * Serializes Collection
     *
     * @return string
     *  The string representation of Collection
     */
    public function serialize() {

        return serialize(
            array( $this -> collection, $this -> references, $this -> iterator )
        );
    }

    /**
     * Unserializes Collection, reconstructing it
     *
     * @param string $serialized
     *  String representation of an Object
     *
     * @return void
     */
    public function unserialize( $serialized ) {

        $data = unserialize( $serialized );

        $this -> collection = $data[ 0 ];
        $this -> references = $data[ 1 ];
        $this -> iterator   = $data[ 2 ];
    }
}