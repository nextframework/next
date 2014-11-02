<?php

namespace Next\Components\Iterator;

use Next\Components\Object;    # Object Class

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
     * Reference Hash Table
     *
     * @var ArrayObject $references
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
     * @see Next\Components\Iterator\AbstractCollection::clear()
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
     * @return Next\Components\Iterator\AbstractCollection
     *  Collection Object (Fluent Interface)
     */
    public function clear() {

        $this -> collection = new \ArrayObject;

        $this -> references = new \ArrayObject;

        $this -> iterator   = $this -> collection -> getIterator();

        return $this;
    }

    // Collection's Manipulation Methods

    /**
     * Adds a new Object to Collection
     *
     * @param Next\Components\Object $object
     *  Object to add to Collection
     *
     * @return Next\Components\Iterator\AbstractCollection
     *  Collection Object (Fluent Interface)
     */
    public function add( Object $object ) {

        if( $this -> accept( $object ) ) {

            // Adding Object...

            $this -> collection -> append( $object );

            // ... and a Reference to it

            $this -> references -> append( $object -> getHash() );
        }

        return $this;
    }

    /**
     * Removes an Object from Collection
     *
     * @param integer $index
     *  Offset to remove
     *
     * @return Next\Components\Iterator\AbstractCollection
     *  Collection Object (Fluent Interface)
     */
    public function remove( $index ) {

        $index = (int) $index;

        // If offset exists in Objects Collection and Objects References...

        if( $this -> collection -> offsetExists( $index )
                && $this -> references -> offsetExists( $index ) ) {

            // ... let's remove them

            $this -> collection -> offsetUnset( $index );

            $this -> references -> offsetUnset( $index );
        }

        return $this;
    }

    /**
     * Check if an Objects exists
     *
     * @param Next\Components\Object $object
     *  Object to check if it's present in Collection
     *
     * @return boolean
     *  TRUE if given Object is already present in Collection and FALSE otherwise
     */
    public function contains( Object $object ) {
        return in_array( $object -> getHash(), (array) $this -> references );
    }

    // Accessors

    /**
     * Get Collection Storage
     *
     * @return ArrayObject
     *  Collection Object
     */
    public function getCollection() {
        return $this -> collection;
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
     * @return Next\Components\Iterator\AbstractCollection
     *  Collection Object (Fluent Interface)
     */
    public function setIterator( $iterator ) {

        $this -> iterator =& $iterator;

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

        $iterator = new $this -> iterator( $this -> collection );

        // If object instance above does not result in a valid Iterator, we'll return the default

        return ( $iterator instanceof \Iterator ? $iterator : new \ArrayIterator( $this -> collection ) );
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
     * @return Next\Components\Iterator\AbstractCollection
     *  Collection Object (Fluent Interface)
     */
    public function unserialize( $serialized ) {

        $this -> collection = new \ArrayObject( unserialize( $serialized ) );

        return $this;
    }
}