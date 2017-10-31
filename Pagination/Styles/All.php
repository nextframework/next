<?php

/**
 * Pagination Style Class: All | Pagination\Style\All.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Pagination\Styles;

use Next\Pagination\Paginator;    # Paginator Class

/**
 * The 'All Elements' Pagination Style builds an Interval with all elements of
 * Paginator's data-source
 *
 * @package    Next\Pagination
 *
 * @uses       Next\Pagination\Paginator;
 *             Next\Pagination\Styles\Style
 */
class All implements Style {

    /**
     * Paginator Object
     *
     * @var Next\Pagination\Paginator $paginator
     */
    protected $paginator;

    /**
     * Lowest Pagination Interval value. Always 1
     * @var integer $lowerBound
     */
    protected $lowerBound = 1;

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
    public function setPaginator( Paginator $paginator ) : All {

        $this -> paginator = $paginator;

        return $this;
    }

    // Boundable Interval Interface Methods Implementation

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
    public function getLowerBound() : int {
        return $this -> lowerBound;
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
    public function getUpperBound() : int {

        $this -> upperBound = count( $this -> paginator );

        return $this -> upperBound;
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
     * @return array
     *  Pagination range
     *
     * @see https://en.wikipedia.org/wiki/Interval_(mathematics)
     */
    public function getInterval() : array {

        if( $this -> lowerBound === NULL || $this -> upperBound === NULL ) {

            $interval = range( $this -> getLowerBound(), $this -> getUpperBound() );

        } else {

            $interval = range( $this -> lowerBound, $this -> upperBound );
        }

        $this -> count = count( $interval );

        return $interval;
    }

    // Countable Interface Method Implementation

    /**
     * Count the number of elements within the Interval
     *
     * @return integer
     *  Number of elements within the Interval
     */
    public function count() : int {
        return ( $this -> count !== NULL ? $this -> count : count( $this -> getInterval() ) );
    }
}