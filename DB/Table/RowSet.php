<?php

namespace Next\DB\Table;

/**
 * Table RowSet Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class RowSet extends AbstractDataGateway implements \Iterator {

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

        foreach( $this -> source as $records ) $count += $records -> update() -> rowCount();

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

        foreach( $this -> source as $records ) $count += $records -> delete() -> rowCount();

        return $count;
    }

    // Method Overwriting

    /**
     * Set Data Source from different sources
     *
     * @param mixed|array $source
     *  Source Data
     *
     * @return array
     *  Input array with its values mapped as an instance of Next\DB\Table\Row
     */
    protected function setSource( $source ) {

        foreach( $source as $offset => $data ) {
            if( $data !== FALSE ) $this -> source[ $offset ] = new Row( $this -> manager, $data );
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

    /**
     * @internal
     * Overloading
     *
     * Allow direct Row manipulation when there is only one Row in the RowSet
     */

    /**
     * Return the value at the specified offset
     *
     * @param mixed $offset
     *  Offset to retrieve value from
     *
     * @return mixed
     *  The value at the specified offset, if exists and FALSE id doesn't
     *
     * @link http://php.net/manual/en/arrayobject.offsetget.php
     *  ArrayObject::offsetGet()
     *
     * @throws Next\DB\Table\DataGatewayException
     *  Thrown if trying to access a Row when the RowSet has more than just one Row
     */
    public function __get( $offset ) {

        if( count( $this ) > 1 ) {
            throw DataGatewayException::accessViolation();
        }

        return $this -> source[ 0 ] -> {$offset};
    }

    /**
     * Set a new value at specified offset
     *
     * @param mixed $offset
     *  Offset to set
     *
     * @param mixed $value
     *  New value
     *
     * @link http://php.net/manual/en/arrayobject.offsetset.php
     *  ArrayObject::offsetSet()
     *
     * @throws Next\DB\Table\DataGatewayException
     *  Thrown if trying to access a Row when the RowSet has more than just one Row
     */
    public function __set( $offset, $value ) {

        if( count( $this ) > 1 ) {
            throw DataGatewayException::accessViolation();
        }

        $this -> source[ 0 ] -> {$offset} = $value;
    }
}