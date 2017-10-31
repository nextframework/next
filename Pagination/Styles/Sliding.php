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
use Next\Pagination\Paginator;                  # Paginator Class

/**
 * The 'Sliding' Pagination Style (a.k.a. Yahoo! Search Style) builds an
 * Interval long as the defined Number of Items per Page putting the cursor in
 * the middle of it and remaining there until approaching the end of it
 *
 * As the Current Page increases, previous pages slides out for the next
 * pages to slide in. E.g:
 *
 * > Considering the default option of 5 Items per Page:
 *
 * Page 1~3: `1 | 2 | 3 | 4 | 5`
 *
 * Page 4: ` 2 | 3 | 4 | 5 | 6`
 *
 * Page 5: `3 | 4 | 5 | 6 | 7`
 *
 * ...
 *
 * Page 24~26: `22 | 23 | 24 | 25 | 26`
 *
 * @package    Next\Pagination
 *
 * @uses       Next\Components\Interfaces\Configurable
 *             Next\Pagination\Paginator
 *             Next\Pagination\Styles\Style
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
     * in Next\Pagination\Paginator Object
     *
     * @var integer $visiblePages
     */
    protected $visiblePages;

    /**
     * Current Page passed on as Parameter Option to the
     * \Next\Pagination\Paginator Object
     *
     * @var integer $currentPage
     */
    protected $currentPage;

    /**
     * Total of Records in data-source provided as Parameter Option to
     * the Next\Pagination\Paginator Object.
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
     * Sets up shortcuts to Next\Pagination\Paginator Parameter Options
     */
    public function configure() {

        $options = $this -> paginator -> getOptions();

        $this -> visiblePages = $options -> visiblePages;
        $this -> currentPage  = $options -> currentPage;

        $this -> total        = count( $this -> paginator );
        $this -> delta        = ceil( $this -> visiblePages / 2 );
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
    public function setPaginator( Paginator $paginator ) : Sliding {

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

        /**
         * @internal
         *
         * Creating a local version of Next\Pagination\Styles\Sliding::$delta
         * to not overwrite the original value on the second if-statement
         * messing with the logics of Sliding::getUpperBound()
         */
        $delta = $this -> delta;

        if( ( $this -> currentPage - $delta ) > ( $this -> total - $this -> visiblePages ) ) {

            $this -> lowerBound = $this -> total - $this -> visiblePages + 1;

            return $this -> lowerBound;
        }

        // We're getting close to end, let's change a little bit

        if( ( $this -> currentPage - $delta ) < 0 ) {
            $delta = $this -> currentPage;
        }

        $this -> lowerBound = ( ( $this -> currentPage - $delta ) + 1 );

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

        /**
         * @internal
         *
         * Creating a local version of Next\Pagination\Styles\Sliding::$delta
         * to not overwrite the original value on the second if-statement
         * messing with the logics of Sliding::getLowerBound()
         */
        $delta = $this -> delta;

        if( ( $this -> currentPage - $delta ) > ( $this -> total - $this -> visiblePages ) ) {

            $this -> upperBound = $this -> total;

            return $this -> upperBound;
        }

        // We're getting close to end, let's change a little bit

        if( ( $this -> currentPage - $delta ) < 0 ) {
            $delta = $this -> currentPage;
        }

        $this -> upperBound = ( $this -> currentPage - $delta ) + $this -> visiblePages;

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
     * To put it simple, this means a range between the Lower Bound and
     * the Upper Bound
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

        return ( $this -> count !== NULL ?
                    $this -> count : count( $this -> getInterval() ) );
    }
}