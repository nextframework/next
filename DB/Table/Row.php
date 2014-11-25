<?php

namespace Next\DB\Table;

/**
 * Table Row Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Row extends AbstractDataGateway {

    /**
     * Ready-to-modify Data Source
     *
     * @var ArrayObject $storage
     */
    private $storage;

    private $knownColumns = array();

    /**
     * Additional Initialization
     */
    public function init() {

        // Initialize an ArrayObject from original Data Source

        $this -> storage = new \ArrayObject(

            (array) $this -> source, \ArrayObject::ARRAY_AS_PROPS
        );

        // Listing Table Columns for update and delete processes

        $this -> knownColumns = array_intersect_key( (array) $this -> source, $this -> manager -> getTable() -> getFields() );
    }

    // Method Overwriting

    /**
     * Get DataSource
     *
     * @return array
     *  Data Source
     */
    public function getSource() {
        return (array) $this -> storage;
    }

    /**
     * Updates the record
     *
     * @return Next\DB\Table\Manager
     *  Table Manager Object
     */
    public function update() {

        // Listing updated fields

        $source = array_diff( (array) $this -> storage, (array) $this -> source );

        /**
         * @internal
         *
         * Listing table columns allow in order to build the UPDATE conditions only
         * with fields present in the table being updated, discarding, for example,
         * joined aliased fields
         */
        $known = array_diff_key( $this -> knownColumns, $source );

        // Starting UPDATE Statement

        $this -> manager -> setSource( $source ) -> update();

        /**
         * @internal
         *
         * The new WHERE Clauses will be all Table columns that are not
         * being modified by this update
         */
        foreach( array_keys( $known ) as $column ) {
            $this -> manager -> where( sprintf( '%1$s = :%1$s', $column ), $known );
        }

        return $this -> manager;
    }

    /**
     * Deletes the record
     *
     * @return Next\DB\Table\Manager
     *  Table Manager Object
     */
    public function delete() {

        /**
         * @internal
         *
         * Different of an UPDATE, for DELETE Statements all Table columns
         * can be used as condition
         */
        foreach( array_keys( $this -> knownColumns ) as $column ) {
            $this -> manager -> where( sprintf( '%1$s = :%1$s', $column ), $this -> knownColumns );
        }

        return $this -> manager -> delete();
    }

    /**
     * @internal
     * Overloading
     *
     * Bridges ArrayObject's ArrayAccess Methods Implementations
     */

    /**
     * Check whether or not an offset exists
     *
     * @param mixed $offset
     *  Offset to test
     *
     * @return boolean
     *  TRUE if the requested index exists and FALSE otherwise
     *
     * @link http://php.net/manual/en/arrayobject.offsetexists.php
     *  ArrayObject::offsetExists()
     */
    public function __isset( $offset ) {
        return $this -> storage -> offsetExists( $offset );
    }

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
     */
    public function __get( $offset ) {
        return $this -> storage -> offsetGet( $offset );
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
     */
    public function __set( $offset, $value ) {
        $this -> storage -> offsetSet( $offset, $value );
    }

    /**
     * Unset the value at the specified index
     *
     * @param mixed $offset
     *  Offset to unset
     *
     * @link http://php.net/manual/en/arrayobject.offsetunset.php
     *  ArrayObject::offsetUnset()
     */
    public function __unset( $offset ) {
        $this -> storage -> offsetUnset( $offset );
    }
}