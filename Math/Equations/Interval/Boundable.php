<?php

/**
 * Boundable Interval Interface | Math\Equations\Interval\Boundable.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Math\Equations\Interval;

/**
 * Boundable Interval Objects work out of lower and upper boundaries in a
 * mathematical interval
 *
 * @package    Next\Math\Equations\Interval
 */
interface Boundable {

    /**
     * Get the Lower Bound of a Subset.
     *
     * A Lower Bound is the lowest 'k' element within all 's' elements
     * in a partially ordered set 'P' of a subset 'S' such as
     * 'k' ≤ 's' for each and all 's within 'S'
     */
    public function getLowerBound();

    /**
     * Get the Upper Bound of a Subset.
     *
     * An Upper Bound is the highest 'k' element within all 's' elements
     * in a partially ordered set 'P' of a subset 'S' such as
     * 'k' ≥ 's' for each and all 's within 'S'
     */
    public function getUpperBound();

    /**
     * Get an Interval.
     *
     * In mathematics, a (real) interval is a set of real numbers with
     * the property that any number that lies between two numbers in
     * the set is also included in the set. For example, the set of all
     * numbers x satisfying 0 ≤ x ≤ 1 is an interval which contains 0 and 1,
     * as well as all numbers between them.
     *
     * @see https://en.wikipedia.org/wiki/Interval_(mathematics)
     */
    public function getInterval() : array;
}