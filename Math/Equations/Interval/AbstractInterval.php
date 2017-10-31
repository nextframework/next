<?php

/**
 * Interval Equations Abstract Class | Math\Equations\Interval\AbstractInterval.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Math\Equations\Interval;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Validation\Verifiable;    # Verifiable Interface
use Next\Components\Object;        # Object Class
use Next\Math\Floats;              # Floats Data-type Class

/**
 * Base structure for all Interval Equations
 *
 * @package    Next\Math
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Validation\Verifiable,
 *             Next\Components\Object,
 *             Next\Math\Floats,
 *             Next\Math\Equations\Interval\Interval
 */
abstract class AbstractInterval extends Object implements Verifiable, Interval {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [

        /**
         * Total of positive results
         */
        'positive'  => [ 'required' => TRUE ],

        /**
         * Total of negative results
         */
        'negative'  => [ 'required' => TRUE ],

        /**
         * Confidence Level
         *
         * @internal
         *
         * In statistics, a Confidence Interval is a type of estimation
         * computed from the observed data.
         * Defaults to '0.95',meaning 95% chance that the results will
         * be likely considered as positive
         */
        'confidence' => 0.95
    ];

    /**
     * Total number of results
     *
     * @var integer $n
     */
    protected $n;

    /**
     * Positive Fraction out of Total Results
     *
     * @var integer $p
     */
    protected $p;

    /**
     * Quantile of the standard normal distribution
     *
     * @internal
     *
     * In statistics and the theory of probability, quantiles are
     * cutpoints dividing the range of a probability distribution into
     * contiguous intervals with equal probabilities, or dividing
     * the observations in a sample in the same way.
     *
     * There is one less quantile than the number of groups created.
     *
     * E.g: Given a Interval with a range from 0...5, the quantile
     * would be '3', distributing the subset with two equal
     * probabilities "above" and two "below"
     *
     * @var float $z
     */
    protected $z;

    /**
     * Additional Initialization.
     * Pre-computes values used by concrete classes
     */
    protected function init() : void {

        $positive   = intval( $this -> options -> positive );
        $negative   = intval( $this -> options -> negative );

        $this -> n = ( $positive + $negative );

        $pNorm = new Floats(
            [ 'value' => ( 1 - ( 1 - $this -> options -> confidence ) / 2 ) ]
        );

        $this -> z = $pNorm -> get();

        $this -> p = ( $this -> n > 0 ? ( 1.0 * $positive ) / $this -> n : 0 );
    }

    // Boundable Interval Interface Method Implementation

    /**
     * Get an Interval
     *
     * @return array
     *  An Interval of Real Numbers
     */
    public function getInterval() : array {
        return [ $this -> getLowerBound(), $this -> getUpperBound() ];
    }

    // Interval Interface Methods Implementation

    /**
     * Get total of Positive Results
     *
     * @return integer
     *  Number of Positives
     */
    public function getPositives() : int {
        return intval( $this -> options -> positive );
    }

    /**
     * Get Total of Negative Results
     *
     * @return integer
     *  Number of Negatives
     */
    public function getNegatives() : int {
        return intval( $this -> options -> negative );
    }

    /**
     * Get Quantile of Standard Normal Distribution
     *
     * @return float
     *  The Quantile
     */
    public function getConfidence() : float {
        return $this -> options -> confidence;
    }

    /**
     * Get the Total number of results
     *
     * @return integer
     *  Total number of Results
     */
    public function getTotal() : int {
        return $this -> n;
    }

    /**
     * Get the Positive Fraction out of Total Results
     *
     * @return integer
     *  Positive Fraction
     */
    public function getPositivesFraction() : int {
        return $this -> p;
    }

    /**
     * Get the Positive Fraction out of Total Results
     *
     * @return integer
     *  Positive Fraction
     */
    public function getQuantile() : int {
        return $this -> z;
    }

    /**
     * Verifies Object Integrity
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if optional Parameter Option 'confidence' has
     *  been overwritten with a value lower than or equal to
     *  zero (0% chance of being considered relevant) -OR- a value
     *  greater or equal to 1 (100% chance)
     */
    public function verify() : void {

        if( $this -> options -> confidence < 0 ||
                $this -> options -> confidence >= 1 ) {

            throw new InvalidArgumentException(
                'Interval Equation requires a positive float lower
                than 1.0 (100%) representing the level of statistical
                confidence'
            );
        }
    }
}