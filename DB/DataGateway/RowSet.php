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

use Next\Components\Object;              # Object Class
use Next\Components\Utils\ArrayUtils;    # ArrayUtils Class

/**
 * DataGateway RowSet Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
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
     * Data-source built with \Next\DB\Entity\Entity Objects for each
     * record present on original source passed as Parameter Option
     *
     * @var array $source
     */
    protected $source = [];

    /**
     * Total of \Next\DB\Entity\Entity Objects in data-source built
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
    protected function init() {

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
    public function update()  {

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
    public function delete()  {

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
     * @return array
     *  Data-source
     */
    public function getSource() {
        return ( $this -> total == 1 ? $this -> source[ 0 ] : $this -> source );
    }

    /**
     * Get a copy of Data-source as array
     *
     * @return array
     *  Data-source as array
     */
    public function getArrayCopy() {
        return ArrayUtils::map( $this -> source );
    }

    // Countable Interface Method Implementation

    /**
     * Count elements on Data-source
     *
     * @return integer
     *  Number of elements in RowSet
     */
    public function count() {
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
    public function next() {
        ++$this -> offset;
    }

    /**
     * Rewind the Iterator to the first element
     */
    public function rewind() {
        $this -> offset = 0;
    }

    /**
     * Check if current position is valid
     *
     * @return boolean
     *  TRUE if current position is valid and FALSE otherwise
     */
    public function valid() {
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
     *  Thrown if trying to test data of a \Next\DB\Entity\Entity
     *  from a RowSet with multiple records
     */
    public function offsetExists( $offset ) {

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
     *  Thrown if trying to access data of a \Next\DB\Entity\Entity column
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
     *  Thrown if trying to modify data of a \Next\DB\Entity\Entity
     *  from a RowSet with multiple records
     */
    public function offsetSet( $offset, $data ) {

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
     * @throws \Next\Exception\Exception\AccessViolationException
     *  Thrown if trying to remove data of a \Next\DB\Entity\Entity
     *  from a RowSet with multiple records
     */
    public function offsetUnset( $offset ) {

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
     * @internal
     *
     * Overloading
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
    public function __set( $offset, $data ) {
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
    public function __isset( $offset ) {
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
    public function __unset( $offset ) {
        $this -> offsetUnset( $offset );
    }

    // Auxiliary Methods

    /**
     * Set Data-source from different sources
     *
     * @param mixed|array|object $source
     *  Source Data
     */
    private function setSource( $source ) {

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