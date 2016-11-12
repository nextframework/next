<?php

namespace Next\DB\Table;

use Next\Components\Utils\ArrayUtils;

/**
 * Table RowSet Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class RowSet extends AbstractDataGateway implements \Iterator, \ArrayAccess {

    /**
     * Iterator Offset
     *
     * @var integer
     */
    private $offset = 0;

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

            // Adding updated model as Table Manager Source

            $this -> manager -> setTable( $records );

            // Adding WHERE Clause based on PRIMARY KEY

            $primary = $records -> getPrimaryKey();

            $this -> manager -> where(
                sprintf( '%1$s = :%1$s', $primary ), array( $primary => $records -> {$primary} )
            );

            $count += $this -> manager -> update() -> rowCount();
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

            $this -> manager -> where(
                sprintf( '%1$s = :%1$s', $primary ), array( $primary => $records -> {$primary} )
            );

            $count += $this -> manager -> delete() -> rowCount();
        }

        return $count;
    }

    // Method Overwriting

    /**
     * Set Data Source from different sources
     *
     * @param mixed|array|object $source
     *  Source Data
     *
     * @return void
     */
    protected function setSource( $source ) {

        foreach( $source as $data ) {

            $table = $this -> manager -> getTable() -> getClass() -> newInstance();

            /**
             * @internal
             *
             * Adding PRIMARY KEY value
             *
             * The value used will be the first value among all fetched data
             * because, usually, the PRIMARY KEY is listed first
             */
            $table -> setPrimaryKey(
                current( array_slice( (array) $data, 0, 1, TRUE ) )
            );

            foreach( (array) $data as $column => $value ) $table -> {$column} = $value;

            $this -> source[] = $table;
        }
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
     * Checks whether or not an offset exists within the RowSet Data Source
     *
     * @param  mixed|string|integer $offset
     *  Offset to search
     *
     * @return boolean
     *  TRUE if given offset exists and FALSE otherwise
     */
    public function offsetExists( $offset ) {
        return ( $this -> source[ $offset ] !== FALSE );
    }

    /**
     * Returns the value stored at given offset in the RowSet Data Source
     *
     * @param  mixed|string|integer $offset
     *  Offset to retrieve data from
     *
     * @return mixed|boolean
     *  Data stored at given offset if it exists and FALSE otherwise
     */
    public function offsetGet( $offset ) {
        return $this -> source[ $offset ];
    }

    /**
     * Assign a value to the specified offset in the RowSet Data Source
     *
     * @param  mixed|string|integer $offset
     *  Offset where new data will be stored
     *
     * @param mixed $data
     *  Data to add
     *
     * @throws Next\DB\Table\TableException
     *  Always thrown as RowSet Data must not be added manually
     */
    public function offsetSet( $offset, $data ) {
        throw TableException::accessViolation();
    }

    /**
     * Removes an Object from RowSet Data Source at given offset
     *
     * @param  mixed|string|integer $offset
     *  Offset to unset
     *
     * @throws Next\DB\Table\TableException
     *  Always thrown as RowSet Data must not be added manually
     */
    public function offsetUnset( $offset ) {
        throw TableException::accessViolation();
    }

    /**
     * @internal
     * Overloading
     *
     * Allows direct manipulation of records when the RowSet has only one entry
     */

    /**
     * Return the value of a column of the first Next\DB\Table\Table defined as source
     *
     * @param mixed|string $column
     *  Column to be retrieved
     *
     * @return mixed|void
     *  The column value, if exists and "nothing" otherwise
     *
     * @throws Next\DB\Table\DataGatewayException
     *  Throw if trying to access data of a Next\DB\Table\Table column
     *  from a RowSet with multiple records
     *
     * @see Next\DB\Table\Table::offsetExists()
     * @see Next\DB\Table\Table::offsetGet()
     */
    public function __get( $column ) {

        $length = count( $this -> source );

        if( $length ==  0 ) return;

        if( $length > 1 ) {
            throw DataGatewayException::accessViolation();
        }

        if( isset( $this -> source[ 0 ][ $column ] ) ) {
            return $this -> source[ 0 ][ $column ];
        }
    }

    /**
     * Sets a new value for a column of the first
     * Next\DB\Table\Table defined as source
     *
     * If the column doesn't exist, it will be created in runtime
     *
     * @param mixed|string $column
     *  Column to modify
     *
     * @param mixed $value
     *  New value
     *
     * @return void
     *
     * @throws Next\DB\Table\DataGatewayException
     *  Throw if trying to modify data of a Next\DB\Table\Table
     *  from a RowSet with multiple records
     *
     * @see Next\DB\Table\Table::offsetExists()
     * @see Next\DB\Table\Table::offsetSet()
     */
    public function __set( $column, $value ) {

        if( count( $this -> source ) > 1 ) {
            throw DataGatewayException::accessViolation();
        }

        $this -> source[ 0 ] -> {$column} = $value;
    }

    /**
     * Checks whether or not a column exists the first
     * Next\DB\Table\Table defined as source
     *
     * @param  mixed|string  $column
     *  Column to check
     *
     * @return boolean
     *  TRUE if desired column exists and FALSE otherwise
     *  If there's nothing defined as source, FALSE is also returned
     *
     * @throws Next\DB\Table\DataGatewayException
     *  Throw if trying to test data of a Next\DB\Table\Table
     *  from a RowSet with multiple records
     *
     * @see Next\DB\Table\Table::offsetExists()
     */
    public function __isset( $column ) {

        $length = count( $this -> source );

        if( $length ==  0 ) return FALSE;

        if( $length > 1 ) {
            throw DataGatewayException::accessViolation();
        }

        return isset( $this -> source[ 0 ] -> {$column} );
    }
}