<?php

/**
 * Pagination Style Class: Standard | Pagination\Style\Standard.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Pagination\Styles;

use Next\Components\Interfaces\Verifiable;                 # Verifiable Interface
use Next\Exception\Exceptions\InvalidArgumentException;    # Invalid Argument Exception Class
use Next\Components\Object;                                # Object Class
use Next\Pagination\Paginator;                               # Paginator Class

/**
 * Standard Pagination Style Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Standard extends Object implements Verifiable, Style {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'before'    => [ 'required' => FALSE, 'default' => 2 ],
        'after'     => [ 'required' => FALSE, 'default' => 2 ]
    ];

    /**
     * Paginator Object
     *
     * @var Next\Pagination\Paginator $paginator
     */
    protected $paginator;

    /**
     * Lowest Pagination Interval value
     *
     * @var integer $lowerBound
     */
    protected $lowerBound;

    /**
     * Highest Pagination Interval value
     *
     * @var integer $upperBound
     */
    protected $upperBound;

    /**
     * Total of Records in Pagination Style Interval
     *
     * @var integer $count
     */
    protected $count;

    // Pagination Style Interface Method Implementation

    /**
     * Set Paginator Object to read Pagination Informations from
     *
     * @param Next\Pagination\Paginator $paginator
     *  Paginator Object
     *
     * @return Next\Pagination\Styles\Style
     *  Pagination Style Object (Fluent Interface)
     */
    public function setPaginator( Paginator $paginator ) {

        $this -> paginator = $paginator;

        return $this;
    }

    // Boundable Interface Methods Implementation

    /**
     * Get the Lower Bound of a Subset.
     *
     * A Lower Bound is the lowest 'k' element within all 's' elements
     * in a partially ordered set 'P' of a subset 'S' such as
     * 'k' ≤ 's' for each and all 's within 'S'
     *
     * In Pagination Interval this means the Lowest Pagination Interval value
     *
     * @return integer
     *  The Lowest Page Number visible
     */
    public function getLowerBound() {

        $this -> lowerBound = ( $this -> paginator -> getCurrentPage() - $this -> options -> before );

        return ( $this -> lowerBound < 1 ? 1 : $this -> lowerBound );
    }

    /**
     * Get the Upper Bound of a Subset.
     *
     * An Upper Bound is the highest 'k' element within all 's' elements
     * in a partially ordered set 'P' of a subset 'S' such as
     * 'k' ≥ 's' for each and all 's within 'S'
     *
     * In Pagination Interval this means the Highest Pagination Interval value
     *
     * @return integer
     *  The highest Page Number visible
     */
    public function getUpperBound() {

        $total = count( $this -> paginator );

        $this -> upperBound = ( $this -> paginator -> getCurrentPage() + $this -> options -> after );

        return ( $this -> upperBound > $total ? $total : $this -> upperBound );
    }

    /**
     * Get an Interval.
     *
     * In mathematics, a (real) interval is a set of real numbers with
     * the property that any number that lies between two numbers in
     * the set is also included in the set. For example, the set of all
     * numbers x satisfying 0 ≤ x ≤ 1 is an interval which contains 0 and 1,
     * as well as all numbers between them.
     *
     * To put it simple, this means a range between the Lower Bound and the Upper Bound
     *
     * @see https://en.wikipedia.org/wiki/Interval_(mathematics)
     */
    public function getInterval() {

        if( $this -> lowerBound === NULL || $this -> upperBound === NULL ) {

            $interval = range( $this -> getLowerBound(), $this -> getUpperBound() );

        } else {

            $interval = range( $this -> lowerBound, $this -> upperBound );
        }

        $this -> count = count( $interval );

        return $interval;
    }

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity
     *
     * @throws Next\Exception\Exceptions\InvalidArgumentException
     *  Throw if initial value of Parameter Option 'before' is not an
     *  integer or it's less than 1
     */
    public function verify() {

        if( ! is_int( $this -> options -> before ) || $this -> options -> before < 1 ) {

            throw new InvalidArgumentException(
                'Parameter Option <strong>before</strong> must be an integer greater or equal 1'
            );
        }
    }

    // Countable Interface Method Implementation

    /**
     * Count the number of elements within the Interval
     *
     * @return integer
     *  Number of elements within the Interval
     */
    public function count() {
        return ( $this -> count !== NULL ? $this -> count : count( $this -> getInterval() ) );
    }
}