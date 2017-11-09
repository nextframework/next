<?php

/**
 * DataGateway RowSet Class | DB\DataGateway\RowSet.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\DataGateway;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\AccessViolationException;
use Next\Exception\Exceptions\UnderflowException;

use Next\Components\Object;              # Object Class
use Next\Components\Utils\ArrayUtils;    # ArrayUtils Class

/**
 * The DataGateway RowSet Class creates an Entity instance for each result
 * in a ResultSet filling their fields with Table Column values
 *
 * Also, being a DataGateway, the resulting Entities can be updated or deleted
 * after being modified
 *
 * @package    Next\DB
 *
 * @uses       Next\Exception\Exceptions\AccessViolationException
 *             Next\Exception\Exceptions\UnderflowException
 *             Next\Components\Object
 *             Next\Components\Utils\ArrayUtils
 *             Next\DB\DataGateway\DataGateway
 *             Iterator
 *             ArrayAccess
 */
class RowSet extends Object implements DataGateway, \Iterator, \ArrayAccess {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'manager' => [ 'type' => 'Next\DB\Entity\Manager', 'required' => TRUE ],
        'source'  => [ 'required' => FALSE, 'default' => [] ]
    ];

    /**
     * Data-source built with Next\DB\Entity\Entity Objects for each
     * record present on original source passed as Parameter Option
     *
     * @var array $source
     */
    protected $source = [];

    /**
     * Total of Next\DB\Entity\Entity Objects in data-source built
     *
     * @var integer $total
     */
    protected $total;

    /**
     * Iterator Offset
     *
     * @var integer
     */
    private $offset = 0;

    /**
     * Additional Initialization.
     * Sets up provided data-source
     *
     * @see \Next\DB\DataGateway\RowSet::setSource()
     */
    protected function init() : void {

        // Setting Up the Source Data

        $this -> setSource( $this -> options -> source );

        $this -> total = count( $this );
    }

    // DataGateway Interface Methods

    /**
     * Updates one or more records
     *
     * @return integer
     *  Total number of affected records
     */
    public function update() : int {

        $count = 0;

        foreach( $this -> source as $records ) {

            // Defining an updated Entity as Entity Manager Source

            $this -> options -> manager -> setEntity( $records );

            // Adding WHERE Clause based on PRIMARY KEY

            $primary = $records -> getPrimaryKey();

            $this -> options -> manager -> where(
                sprintf( '%1$s = :%1$s', $primary ), [ $primary => $records -> {$primary} ]
            );

            $count += $this -> options -> manager -> update() -> rowCount();
        }

        return $count;
    }

    /**
     * Deletes one or more records
     *
     * @return integer
     *  Total number of deleted records
     */
    public function delete() : int {

        $count = 0;

        foreach( $this -> source as $records ) {

            // Adding WHERE Clause based on PRIMARY KEY

            $primary = $records -> getPrimaryKey();

            $this -> options -> manager -> where(
                sprintf( '%1$s = :%1$s', $primary ), [ $primary => $records -> {$primary} ]
            );

            $count += $this -> options -> manager -> delete() -> rowCount();
        }

        return $count;
    }

    // DataGateway Interface Methods Implementation

    /**
     * Get Data-source
     *
     * @return array|\Next|DB\Entity\Entity
     *  If more than one record exists all of them will be returned as an
     *  array of Entity objects
     *  Otherwise, the only Entity Object will be returned directly
     */
    public function getSource() {
        return $this -> source;
    }

    /**
     * Get a copy of Data-source as recursively mapped as array
     *
     * @return array
     *  Data-source as array
     */
    public function getArrayCopy() : array {
        return ArrayUtils::map( $this -> getSource() );
    }

    // Countable Interface Method Implementation

    /**
     * Count elements on Data-source
     *
     * @return integer
     *  Number of elements in RowSet
     */
    public function count() : int {
        return ( $this -> total === NULL ? count( $this -> source ) : $this -> total );
    }

    // Iterator Interface Methods Implementation

    /**
     * Return the current element
     *
     * @return mixed
     *  Current element value
     */
    public function current() {
        return $this -> source[ $this -> offset ];
    }

    /**
     * Return the key of the current element
     *
     * @return scalar
     *  Current element key
     */
    public function key() {
        return $this -> offset;
    }

    /**
     * Move forward to next element
     */
    public function next() : void {
        ++$this -> offset;
    }

    /**
     * Rewind the Iterator to the first element
     */
    public function rewind() : void {
        $this -> offset = 0;
    }

    /**
     * Check if current position is valid
     *
     * @return boolean
     *  TRUE if current position is valid and FALSE otherwise
     */
    public function valid() : bool {
        return array_key_exists( $this -> offset, $this -> source );
    }

    // ArrayAccess Interface Methods Implementation

    /**
     * Checks whether or not an offset exists within the
     * RowSet Data-source
     *
     * @param mixed|string|integer $offset
     *  Offset to search
     *
     * @return boolean
     *  TRUE if given offset exists and FALSE otherwise
     *
     * @throws \Next\Exception\Exception\AccessViolationException
     *  Thrown if trying to test data of a Next\DB\Entity\Entity
     *  from a RowSet with multiple records
     */
    public function offsetExists( $offset ) : bool {

        if( $this -> total > 1 ) {

            throw new AccessViolationException(

                'Data-source has more than one record and therefore
                cannot be directly manipulated'
            );
        }

        return isset( $this -> source[ 0 ] -> {$offset} );
    }

    /**
     * Returns the value stored at given offset in the
     * RowSet Data-source
     *
     * @param mixed|string|integer $offset
     *  Offset to retrieve data from
     *
     * @return mixed|boolean
     *  Data stored at given offset if it exists and FALSE otherwise
     *
     * @throws \Next\Exception\Exception\AccessViolationException
     *  Thrown if trying to access data of a Next\DB\Entity\Entity column
     *  from a RowSet with multiple records
     */
    public function offsetGet( $offset ) {

        if( $this -> total > 1 ) {

            throw new AccessViolationException(

                'Data-source has more than one record and therefore
                cannot be directly manipulated'
            );
        }

        if( isset( $this -> source[ 0 ][ $offset ] ) ) {
            return $this -> source[ 0 ][ $offset ];
        }

        return FALSE;
    }

    /**
     * Assigns a value to the specified offset in the
     * RowSet Data-source
     *
     * @param mixed|string|integer $offset
     *  Offset where new data will be stored
     *
     * @param mixed $data
     *  Data to add
     *
     * @throws \Next\Exception\Exception\AccessViolationException
     *  Thrown if trying to modify data of a Next\DB\Entity\Entity
     *  from a RowSet with multiple records
     */
    public function offsetSet( $offset, $data ) : void {

        if( $this -> total > 1 ) {

            throw new AccessViolationException(

                'Data-source has more than one record and therefore
                cannot be directly manipulated'
            );
        }

        $this -> source[ 0 ] -> {$offset} = $data;
    }

    /**
     * Removes a value at given offset in the RowSet Data-source
     *
     * @param mixed|string|integer $offset
     *  Offset to unset
     *
     * @throws \Next\Exception\Exceptions\UnderflowException
     *  Thrown if trying to remove an Entity Object when the RowSet has none
     *
     * @throws \Next\Exception\Exception\AccessViolationException
     *  Thrown if trying to remove data of a Next\DB\Entity\Entity
     *  from a RowSet with multiple records
     */
    public function offsetUnset( $offset ) : void {

        if( $this -> total == 0 ) {
            throw new UnderflowException( 'RowSet has no Entities' );
        }

        if( $this -> total > 1 ) {

            throw new AccessViolationException(

                'Data-source has more than one record and therefore
                cannot be directly manipulated'
            );
        }

        if( isset( $this -> source[ 0 ] -> {$offset} ) ) {
            unset( $this -> source[ 0 ] -> {$offset} );
        }
    }

    /**
     * Overloading
     *
     * @internal
     *
     * Allows direct manipulation of records when the RowSet has only one entry
     */

    /**
     * Returns the value stored at given offset in the
     * RowSet Data-source
     *
     * @param mixed|string|integer $offset
     *  Offset to retrieve data from
     *
     * @return mixed|boolean
     *  Data stored at given offset if it exists and FALSE otherwise
     *
     * @see \Next\DB\DataGateway\RowSet::offsetGet()
     */
    public function __get( $offset ) {
        return $this -> offsetGet( $offset );
    }

    /**
     * Assigns a value to the specified offset in the
     * RowSet Data-source
     *
     * @param mixed|string|integer $offset
     *  Offset where new data will be stored
     *
     * @param mixed $data
     *  Data to add
     *
     * @see \Next\DB\DataGateway\RowSet::offsetSet()
     */
    public function __set( $offset, $data ) : void {
        $this -> offsetSet( $offset, $data );
    }

    /**
     * Checks whether or not an offset exists within the
     * RowSet Data-source
     *
     * @param mixed|string|integer $offset
     *  Offset to search
     *
     * @return boolean
     *  TRUE if given offset exists and FALSE otherwise
     *
     * @see \Next\DB\DataGateway\RowSet::offsetExists()
     */
    public function __isset( $offset ) : bool {
        return $this -> offsetExists( $offset );
    }

    /**
     * Removes a value at given offset in the RowSet Data-source
     *
     * @param mixed|string|integer $offset
     *  Offset to unset
     *
     * @see \Next\DB\DataGateway\RowSet::offsetUnset()
     */
    public function __unset( $offset ) : void {
        $this -> offsetUnset( $offset );
    }

    // Auxiliary Methods

    /**
     * Set Data-source from different sources
     *
     * @param mixed|array|object $source
     *  Source Data
     */
    private function setSource( $source ) : void {

        foreach( $source as $data ) {

            $entity = $this -> options
                            -> manager
                            -> getEntity()
                            -> getClass()
                            -> newInstance();

            /**
             * @internal
             *
             * Adding PRIMARY KEY value
             *
             * The value used will be the first value among all fetched data
             * because, usually, the PRIMARY KEY is listed first
             */
            $entity -> setPrimaryKey(
                current( array_slice( (array) $data, 0, 1, TRUE ) )
            );

            foreach( (array) $data as $column => $value ) {
                $entity -> {$column} = $value;
            }

            $this -> source[] = $entity;
        }
    }
}