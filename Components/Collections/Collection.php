<?php

/**
 * Collections Component Abstract Class | Components\Collection\Collection.php
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
use Next\Exception\Exceptions\UnderflowException;

use Next\Components\Object;              # Object Class
use Next\Components\Utils\ArrayUtils;    # ArrayUtils Class

/**
 * Base structure for an Objects' Collection
 *
 * @package    Next\Components\Collections
 *
 * @uses       Next\Components\Object
 *             Next\Components\Utils\ArrayUtils
 *             Countable
 *             IteratorAggregate
 *             Serializable
 */
abstract class Collection extends Object
    implements \Countable, \IteratorAggregate, \Serializable {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [

        'collection' => [ 'required' => FALSE, 'default' => [] ]
    ];

    /**
     * Collection Storage
     *
     * @var array $collection
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
     * @see \Next\Components\Collection\Collection::clear()
     */
    protected function init() : void {

        $this -> clear();

        if( count( $this -> options -> collection ) > 0 ) {

            foreach( $this -> options -> collection as $object ) {
                $this -> add( $object );
            }
        }
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
     * @return \Next\Components\Collection\Collection
     *  Collection Object (Fluent Interface)
     */
    public function clear() : Collection {

        $this -> collection = [];

        $this -> references = [];

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
     * @return \Next\Components\Collection\Collection
     *  Collection Object (Fluent Interface)
     */
    public function add( Object $object, $offset = NULL ) : Collection {

        // Check Object acceptance

        if( ! $this -> accept( $object ) ) return $this;

        // Adding Object and References at specific offset

        if( $offset !== NULL ) {

            $this -> collection[ $offset ] = $object;

            $this -> references[ $offset ] = [

                'name' => (string) $object,
                'hash' => $object -> hash()
            ];

        } else {

            // Or at the end of the Collection

            $this -> collection[] = $object;

            $this -> references[] = [

                'name' => (string) $object,
                'hash' => $object -> hash()
            ];
        }

        return $this;
    }

    /**
     * Adds a new Object to the beginning of Collection
     *
     * @param \Next\Components\Object $object
     *  Object to prepend to Collection
     *
     * @return \Next\Components\Collection\Collection
     *  Collection Object (Fluent Interface)
     */
    public function prepend( Object $object ) : Collection {

        if( $this -> accept( $object ) ) {

            array_unshift( $this -> collection, $object );

            array_unshift(

                $this -> references,

                [ 'name' => (string) $object, 'hash' => $object -> hash() ]
            );
        }

        return $this;
    }

    /**
     * Removes an Object from Collection
     *
     * @param mixed|integer|string|\Next\Components\Object $reference
     *  Offset to remove
     *
     * @return \Next\Components\Collection\Collection
     *  Collection Object (Fluent Interface)
     *
     * @throws \Next\Exception\Exceptions\UnderflowException
     *  Thrown if trying to remove an Object from an empty Collection
     */
    public function remove( $reference ) : Collection {

        if( count( $this ) == 0 ) {
            throw new UnderflowException( 'Collection is empty' );
        }

        $i = $this -> find( $reference );

        if( $i != -1 ) {
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
    public function contains( $reference ) : bool {

        /**
         * @internal
         *
         * Differently of `Next\Components\Collections\Lists::item()` tha
         * accepts integers as possible reference (direct offset), here,
         * such type is prohibited as we're testing if an Object —
         * or a reference to it — exists in the Collection, instead of trying
         * to find one for manipulation
         */
        if( is_int( $reference ) ) return -1;

        return ( $this -> find( $reference ) != -1 );
    }

    /**
     * Shuffles Collection while keeping the References Table relation
     *
     * @return \Next\Components\Collection\Collection|void
     *  Collection Object (Fluent Interface)
     */
    public function shuffle() : Collection {

        if( ! empty( $this -> collection ) ) {

            $order = range( 1, count( $this -> collection ) );

            shuffle( $order );

            array_multisort( $order, $this -> collection, $this -> references );
        }

        return $this;
    }

    /**
     * Shifts the first element off the Collection, regardless
     * of its index within it, therefore it's not affected by
     * Collection::shuffle(), for example
     *
     * @param boolean $shift
     *  If TRUE the first element will be *really* removed from the
     *  Collection before being returned, along with its metadata.
     *
     *  If FALSE (default) it will be just retrieved, without
     *  affecting Collection's data
     *
     * @return \Next\Components\Object
     *  If the Collection has any elements, its first Object will
     *  be returned. Otherwise, nothing is returned
     *
     * @throws \Next\Exception\Exceptions\UnderflowException
     *  Thrown if trying to shift an Object from an empty Collection
     *
     * @see \Next\Components\Collections\Collection::shuffle()
     * @see \Next\Components\Collections\Collection::remove()
     */
    public function shift( $shift = FALSE ) :? Object {

        if( count( $this ) == 0 ) {
            throw new UnderflowException( 'Collection is empty' );
        }

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
     * Collection::shuffle(), for example
     *
     * @param boolean $pop
     *  If TRUE the last element will be *really* removed from the
     *  Collection before being returned, along with its metadata.
     *
     *  If FALSE (default) it will be just retrieved, without
     *  affecting Collection's data
     *
     * @return \Next\Components\Object
     *  If the Collection has any elements, its last Object will
     *  be returned. Otherwise, nothing is returned
     *
     * @throws \Next\Exception\Exceptions\UnderflowException
     *  Thrown if trying to pop an Object from an empty Collection
     *
     * @see \Next\Components\Collections\Collection::shuffle()
     * @see \Next\Components\Collections\Collection::remove()
     */
    public function pop( $pop = FALSE ) :? Collection {

        if( count( $this ) == 0 ) {
            throw new UnderflowException( 'Collection is empty' );
        }

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
     *  It can be a string, an integer or an Next\Components\Object object
     *
     * @return integer
     *  Returns the offset of what is being searched if located within
     *  the Collection and -1 if Collection is empty or if can't be found
     *
     * @see \Next\Components\Utils\ArrayUtils::search()
     */
    public function find( $reference ) : int {

        if( empty( $this -> collection ) ) return -1;

        // If an Object object is informed, let's search by hash

        if( $reference instanceof Object ) {

            $hash = $reference -> hash();

            if( ( $offset = ArrayUtils::search( $this -> references, $hash, 'hash' ) ) !== -1 ) {
                return $offset;
            }

            return  -1;
        }

        /**
         * @internal
         *
         * If an integer (not a numeric string) greater than or equal
         * to zero is informed -AND- it exists within the Collection
         * let's return it "as is"
         */
        if( ( is_int( $reference ) && $reference >= 0 ) &&
                array_key_exists( $reference, $this -> collection ) ) {

            return $reference;
        }

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
    public function isEmpty() : boolean {
        return count( $this -> collection == 0 );
    }

    /**
     * Get Collection
     *
     * @return array
     *  The Collection
     */
    public function getCollection() : array {
        return $this -> collection;
    }

    /**
     * Get Collection Metadata
     *
     * @internal
     *
     * Mostly for debugging purposes only
     *
     * @return array
     *  Collection's Metadata
     */
    public function getReferences() : array {
        return $this -> references;
    }

    // Abstract Methods Definition

    /**
     * Check Object acceptance
     *
     * @param \Next\Components\Object $object
     *  Object to have its acceptance in Collection checked
     */
    abstract protected function accept( Object $object ) : bool;

    // Countable Interface Method Implementation

    /**
     * Count elements in Collection
     *
     * @return integer
     *  The number of Objects present in Collection
     */
    public function count() : int {
        return count( $this -> collection );
    }

    // IteratorAggregate Interface Method

    /**
     * Set an External Iterator
     *
     * @param string|Iterator $iterator
     *  A valid Iterator or a classname string of a valid Iterator
     *
     * @return \Next\Components\Collection\Collection
     *  Collection Object (Fluent Interface)
     */
    public function setIterator( $iterator ) : Collection {

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
    public function getIterator() : \Iterator {

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
    public function serialize() : string {

        return serialize(
            [ $this -> collection, $this -> references, $this -> iterator ]
        );
    }

    /**
     * Unserializes Collection, reconstructing it
     *
     * @param string $serialized
     *  String representation of an Object
     */
    public function unserialize( $serialized ) : void {

        $data = unserialize( $serialized );

        $this -> collection = $data[ 0 ];
        $this -> references = $data[ 1 ];
        $this -> iterator   = $data[ 2 ];
    }
}