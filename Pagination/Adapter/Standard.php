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

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Validation\Verifiable;   # Verifiable interface
use Next\Components\Object;       # Object Class

/**
 * The Standard Pagination Adapter simply returns a slice of given data-source,
 * from the starting offset and up to the Number of Items per Page
 *
 * @package    Next\Pagination
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Validation\Verifiable
 *             Next\Components\Object
 *             Next\Pagination\Adapter\Adapter
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
     * Method implemented as interface compliance only
     *
     * @param integer $offset
     *  Offset to start the range
     *
     * @param integer $visiblePages
     *  Number of Items per Page
     *
     * @return array|iterable
     *  Range of pages
     */
    public function getitems( $offset, $visiblePages ) : iterable {
        return new array_slice( $this -> options -> source, $offset, $visiblePages );
    }

    // Countable Interface Method Implementation

    /**
     * Count Pagination Data-source
     *
     * @return integer
     *  Number of elements present in given source
     */
    public function count() : int {
        return count( $this -> options -> source );
    }

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity
     *
     * @throws Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if required Parameter Option 'source' is not an array
     */
    public function verify() : void {

        if( (array) $this -> options -> source !== $this -> options -> source ) {

            throw new InvalidArgumentException(
                'Parameter Option <strong>source</strong> must be an array'
            );
        }
    }
}