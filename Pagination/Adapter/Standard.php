<?php

/**
 * Pagination Standard Adapter Class | Pagination\Adapter\Standard.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Pagination\Adapter;

use Next\Components\Interfaces\Verifiable;                 # Verifiable interface
use Next\Exception\Exceptions\InvalidArgumentException;    # Invalid Argument Exception Class
use Next\Components\Object;                                # Object Class

/**
 * Standard Pagination Adapter Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Standard extends Object implements Verifiable, Adapter {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'source' => [ 'required' => TRUE ]
    ];

    // Adapter Interface Method Implementation

    /**
     * Get items from given offset
     *
     * @param integer $offset
     *  Offset to start the range
     *
     * @param integer $itemsPerPage
     *  Number of Items per Page
     *
     * @return array
     *  Range of pages
     */
    public function getItems( $offset, $itemsPerPage ) {
        return new \ArrayObject( array_slice( $this -> options -> source, $offset, $itemsPerPage ) );
    }

    // Countable Interface Method Implementation

    /**
     * Count Pagination Data-source
     *
     * @return integer
     *  Number of elements present in given source
     */
    public function count() {
        return count( $this -> options -> source );
    }

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity
     *
     * @throws Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if required Parameter Option 'source' is not an array
     */
    public function verify() {

        if( (array) $this -> options -> source !== $this -> options -> source ) {

            throw new InvalidArgumentException(
                'Parameter Option <strong>source</strong> must be an array'
            );
        }
    }
}