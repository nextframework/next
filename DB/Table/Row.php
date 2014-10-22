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

    /**
     * Additional Initialization
     */
    public function init() {

        // Initialize an ArrayObject from original Data Source

        $this -> storage = new \ArrayObject(

            (array) $this -> source, \ArrayObject::ARRAY_AS_PROPS
        );
    }

    // Method Overwriting

    /**
     * Get DataSource
     *
     * @return array
     *   Data Source
     */
    public function getSource() {
        return (array) $this -> storage;
    }

    /**
     * Updates one or more records
     *
     * @return Next\DB\Table\Manager
     *  Table Manager Object
     */
    public function update() {

        $updatedData = array_diff( (array) $this -> storage, (array) $this -> source );

        $conditions  = array_intersect( (array) $this -> storage, (array) $this -> source );

        $this -> manager -> reset();

        $this -> manager -> setSource( $updatedData )
                                      -> update();

        foreach( $conditions as $field => $condition ) {

            $this -> manager -> where( sprintf( '%s = ?', $field ), NULL );
        }

        $this -> manager -> setReplacements( array_values( $conditions ) );

        return $this -> manager;
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
     *   Offset to test
     *
     * @return boolean
     *   TRUE if the requested index exists and FALSE otherwise
     *
     * @link http://php.net/manual/en/arrayobject.offsetexists.php
     *   ArrayObject::offsetExists()
     */
    public function __isset( $offset ) {
        return $this -> storage -> offsetExists( $offset );
    }

    /**
     * Return the value at the specified offset
     *
     * @param mixed $offset
     *   Offset to retrieve value from
     *
     * @return mixed
     *   The value at the specified offset, if exists and FALSE id doesn't
     *
     * @link http://php.net/manual/en/arrayobject.offsetget.php
     *   ArrayObject::offsetGet()
     */
    public function __get( $offset ) {
        return $this -> storage -> offsetGet( $offset );
    }

    /**
     * Set a new value at specified offset
     *
     * @param mixed $offset
     *   Offset to set
     *
     * @param mixed $value
     *   New value
     *
     * @link http://php.net/manual/en/arrayobject.offsetset.php
     *   ArrayObject::offsetSet()
     */
    public function __set( $offset, $value ) {
        $this -> storage -> offsetSet( $offset, $value );
    }

    /**
     * Unset the value at the specified index
     *
     * @param mixed $offset
     *   Offset to unset
     *
     * @link http://php.net/manual/en/arrayobject.offsetunset.php
     *   ArrayObject::offsetUnset()
     */
    public function __unset( $offset ) {
        $this -> storage -> offsetUnset( $offset );
    }
}