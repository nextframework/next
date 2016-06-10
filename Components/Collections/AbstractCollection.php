<?php

namespace Next\Components\Collections;

use Next\Components\Object;              # Object Class

use Next\Components\Utils\ArrayUtils;    # ArrayUtils Class

/**
 * Collection Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
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
     * Collection Constructor
     *
     * Delegates startup routines in order to allow Collection to be cleared
     * by the user too
     *
     * @see Next\Components\Collection\AbstractCollection::clear()
     */
    public function __construct() {

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
     * @return Next\Components\Collection\AbstractCollection
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
     * @param Next\Components\Object $object
     *  Object to add to Collection
     *
     * @return Next\Components\Collection\AbstractCollection
     *  Collection Object (Fluent Interface)
     */
    public function add( Object $object, $offset = NULL ) {

        if( $this -> accept( $object ) ) {

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
        }

        return $this;
    }

    /**
     * Removes an Object from Collection
     *
     * @param mixed|integer|string $reference
     *  Offset to remove
     *
     * @return Next\Components\Collection\AbstractCollection
     *  Collection Object (Fluent Interface)
     */
    public function remove( $reference ) {

        // Nothing to remove

        if( empty( $this -> collection ) ) return;

        $index = -1;

        // Removing straight by element index

        if( is_int( $reference ) ) {

            $index = $reference;

        } else {

            // Locating Object index by Hash

            /**
             * Since all objects *should* extend the Object class, even if
             * Object::getHash() is overwritten with a different implementation,
             * all Objects within a Collection will, probably, have the same
             * hash strategy adopted, so we can use this premise to compute
             * the size of the hash string of all Objects stored
             */
            $first = $this -> shift();

            if( $first === NULL ) return $index;

            if( strlen( $reference ) == strlen( $first -> getHash() ) ) {

                // Trying to find a hash in References Table

                $index = ArrayUtils::search(
                    $this -> references, $reference, 'hash'
                );

            } else {

                // Locating Object index by name

                $index = ArrayUtils::search(
                    $this -> references, $reference, 'name'
                );
            }
        }

        if( $index !== FALSE && $index != -1 && array_key_exists( $index, $this -> collection ) ) {

            unset( $this -> collection[ $index ], $this -> references[ $index ] );
        }

        return $this;
    }

    /**
     * Check if an Objects exists
     *
     * @param mixed|string|Next\Components\Object $element
     *  An Object object or the name of an Object to check if
     *  it's present in Collection
     *
     * @return boolean
     *  TRUE if given Object is already present in Collection and FALSE otherwise
     */
    public function contains( $element ) {

        $element = ( $element instanceof Object ? $element -> getHash() : (string) $element );

        return ( ArrayUtils::search( $this -> references, $element ) != -1 );
    }

    /**
     * Shuffles Collection while keeping the References Table relation
     *
     * @return Next\Components\Collection\AbstractCollection|void
     *  Collection Object (Fluent Interface), if Collection has elements
     */
    public function shuffle() {

        // Nothing to shuffle

        if( empty( $this -> collection ) ) return;

        $count = count( $this -> collection );
        $order = range( 1, $count );

        shuffle( $order );

        array_multisort( $order, $this -> collection, $this -> references );

        return $this;
    }

    /**
     * Shifts the first element off the Collection, regardless
     * of its index within it, therefore it's not affected by
     * AbstractCollection::shuffle(), for example
     *
     * @param  boolean $shift
     *  If TRUE the first element will be *really* removed from the
     *  Collection before being returned, along with its metadata.
     *
     *  If FALSE (default) it will be just retrieved, without
     *  affecting Collection's data
     *
     * @return Next\Components\Object
     *  First Object added to Collection, if Collection has any elements
     *
     * @see Next\Components\Collections\AbstractCollection::shuffle()
     * @see Next\Components\Collections\AbstractCollection::remove()
     */
    public function shift( $shift = FALSE ) {

        // Nothing to shift

        if( empty( $this -> collection ) ) return;

        reset( $this -> collection );
        reset( $this -> references );

        $object = current( $this -> collection );

        if( $shift !== FALSE ) {
            $this -> remove( 0 );
        }

        return $object;
    }

    /**
     * Pops the last element off the Collection, regardless
     * of its index within it, therefore it's not affected by
     * AbstractCollection::shuffle(), for example
     *
     * @param  boolean $pop
     *  If TRUE the last element will be *really* removed from the
     *  Collection before being returned, along with its metadata.
     *
     *  If FALSE (default) it will be just retrieved, without
     *  affecting Collection's data
     *
     * @return Next\Components\Object
     *  Last Object added to Collection, if Collection has any elements
     *
     * @see Next\Components\Collections\AbstractCollection::shuffle()
     * @see Next\Components\Collections\AbstractCollection::remove()
     */
    public function pop( $pop = FALSE ) {

        // Nothing to pop

        if( empty( $this -> collection ) ) return;

        end( $this -> collection );
        end( $this -> references );

        $index = key( $this -> collection );

        $object = $this -> collection[ $index ];

        if( $pop !== FALSE ) {
            $this -> remove( $index );
        }

        return $object;
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
     * @param Next\Components\Object $object
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
     * @return Next\Components\Collection\AbstractCollection
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
     *  The string representation fo Collection
     */
    public function serialize() {
        return serialize( $this -> collection );
    }

    /**
     * Unserializes Collection, reconstructing it
     *
     * @param string $serialized
     *  String representation of an Object
     *
     * @return Next\Components\Collection\AbstractCollection
     *  Collection Object (Fluent Interface)
     */
    public function unserialize( $serialized ) {

        $this -> collection = unserialize( $serialized );

        return $this;
    }
}