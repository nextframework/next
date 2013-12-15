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

            $count += $records -> update() -> rowCount();
        }

        return $count;
    }

    // Method Overwriting

    /**
     * Set Data Source from different sources
     *
     * @param ArrayIterator $source
     *   Source Data
     *
     * @return ArrayIterator
     *   Input ArrayIterator with its values modified to be instance of
     *   Next\DB\Table\Row
     */
    protected function setSource( $source ) {

        parent::setSource( $source );

        $manager =& $this -> manager;

        iterator_apply(

            $this -> source,

            function( \Iterator $iterator ) use( $manager ) {

                $iterator -> offsetSet(

                    $iterator -> key(),

                    new Row( $manager, $iterator -> current() )
                );

                return TRUE;
            },

            array( $this -> source )
        );

        return $this -> source;
    }

    // Iterator Interface Methods Implementation

    /**
     * Return the current element
     *
     * @return mixed
     *   Current element value
     */
    public function current() {
        return $this -> source -> current();
    }

    /**
     * Return the key of the current element
     *
     * @return scalar
     *   Current element key
     */
    public function key() {
        return $this -> source -> key();
    }

    /**
     * Move forward to next element
     */
    public function next() {
        $this -> source -> next();
    }

    /**
     * Rewind the Iterator to the first element
     */
    public function rewind() {
        $this -> source -> rewind();
    }

    /**
     * Check if current position is valid
     *
     * @return boolean
     *   TRUE if current position is valid and FALSE otherwise
     */
    public function valid() {
        return $this -> source -> valid();
    }
}