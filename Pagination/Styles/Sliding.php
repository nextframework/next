<?php

/**
 * Pagination Style Class: Sliding | Pagination\Style\Sliding.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Pagination\Styles;

use Next\Components\Interfaces\Configurable;    # Configurable Interface
use Next\Pagination\Paginator;                    # Paginator Class

/**
 * Sliding Pagination Style Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Sliding implements Configurable, Style {

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

    /**
     * Number of Items displayed per page as defined as Parameter Option
     * in \Next\Pagination\Paginator Object
     *
     * @var integer $itemsPerPage
     */
    protected $itemsPerPage;

    /**
     * Current Page passed on as Paramter Option to the
     * \Next\Pagination\Paginator Object
     *
     * @var integer $currentPage
     */
    protected $currentPage;

    /**
     * Total of Records in data-source provided as Parameter Option to
     * the \Next\Pagination\Paginator Object.
     *
     * @var integer $total
     */
    protected $total;

    /**
     * Part of Lower and Upper Bounds Calculations the Delta is the
     * rounded up quotient of Number of Items per page divided by 2
     *
     * @var integer $delta
     */
    protected $delta;

    /**
     * Post-initialization Configuration
     * Sets up shortcuts to \Next\Pagination\Paginator Parameter Options
     */
    public function configure() {

        $options = $this -> paginator -> getOptions();

        $this -> itemsPerPage = $options -> itemsPerPage;
        $this -> currentPage  = $options -> currentPage;

        $this -> total        = count( $this -> paginator );
        $this -> delta        = ceil( $this -> itemsPerPage / 2 );
    }

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

        /**
         * @internal
         *
         * Creating a local version of \Next\Pagination\Styles\Sliding::$delta
         * to not overwrite the original value on the second if-statement
         * messing with the logics of Sliding::getUpperBound()
         */
        $delta = $this -> delta;

        if( ( $this -> currentPage - $delta ) > ( $this -> total - $this -> itemsPerPage ) ) {

            $this -> lowerBound = $this -> total - $this -> itemsPerPage + 1;

        } else {

            // We're getting close to end, let's change a little bit

            if( ( $this -> currentPage - $delta ) < 0 ) {
                $delta = $this -> currentPage;
            }

            $this -> lowerBound = ( ( $this -> currentPage - $delta ) + 1 );
        }

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
    public function getUpperBound() {

        /**
         * @internal
         *
         * Creating a local version of \Next\Pagination\Styles\Sliding::$delta
         * to not overwrite the original value on the second if-statement
         * messing with the logics of Sliding::getLowerBound()
         */
        $delta = $this -> delta;

        if( ( $this -> currentPage - $delta ) > ( $this -> total - $this -> itemsPerPage ) ) {

            $this -> upperBound = $this -> total;

        } else {

            // We're getting close to end, let's change a little bit

            if( ( $this -> currentPage - $delta ) < 0 ) {
                $delta = $this -> currentPage;
            }

            $this -> upperBound = ( $this -> currentPage - $delta ) + $this -> itemsPerPage;
        }

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