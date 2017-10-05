<?php

/**
 * Interval Equations Abstract Class | Math\Equations\Intervals\AbstractInterval.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Math\Equations\Intervals;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Interfaces\Verifiable;    # Verifiable Interface
use Next\Math\Equations\Equation;             # Equations Interface
use Next\Components\Object;                   # Object Class

/**
 * Abstracts the common components of Interval Equations
 *
 * @package    Next\Math
 *
 * @uses       Next\Components\Interfaces\Verifiable,
 *             Next\Math\Equations\Equation,
 *             Next\Math\Equations\Intervals\Interval,
 *             Next\Components\Object,
 */
abstract class AbstractInterval extends Object implements Verifiable, Equation, Interval {

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
    public function init() {

        $positive   = intval( $this -> options -> positive );
        $negative   = intval( $this -> options -> negative );

        $this -> n = ( $positive + $negative );

        $this -> z = $this -> pNorm(
            ( 1 - ( 1 - $this -> options -> confidence ) / 2 )
        );

        $this -> p = ( $this -> n > 0 ? ( 1.0 * $positive ) / $this -> n : 0 );
    }

    // Boundable Interface Method Implementation

    /**
     * Get an Interval
     *
     * @return array
     *  An Interval of Real Numbers
     */
    public function getInterval() {
        return [ $this -> getLowerBound(), $this -> getUpperBound() ];
    }

    // Interval Interface Methods Implementation

    /**
     * Get total of Positive Results
     *
     * @return integer
     *  Number of Positives
     */
    public function getPositives() {
        return intval( $this -> options -> positive );
    }

    /**
     * Get Total of Negative Results
     *
     * @return integer
     *  Number of Negatives
     */
    public function getNegatives() {
        return intval( $this -> options -> negative );
    }

    /**
     * Get Quantile of Standard Normal Distribution
     *
     * @return float
     *  The Quantile
     */
    public function getConfidence() {
        return $this -> options -> confidence;
    }

    /**
     * Get the Total number of results
     *
     * @return integer
     *  Total number of Results
     */
    public function getTotal() {
        return $this -> n;
    }

    /**
     * Get the Positive Fraction out of Total Results
     *
     * @return integer
     *  Positive Fraction
     */
    public function getPositivesFraction() {
        return $this -> p;
    }

    /**
     * Get the Positive Fraction out of Total Results
     *
     * @return integer
     *  Positive Fraction
     */
    public function getQuantile() {
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
    public function verify() {

        if( $this -> options -> confidence < 0 ||
                $this -> options -> confidence >= 1 ) {

            throw new InvalidArgumentException(
                'Interval Equation requires a positive float lower
                than 1.0 (100%) representing the level of statistical
                confidence'
            );
        }
    }

    // Auxiliary Methods

    /**
     * Get the inverse of normal distribution (p-Norm)
     * p-Norm returns the integral from `\(-\infty\)` to \(q\) of the
     * PDF (Probability Density Function) of the normal distribution
     *
     * @param float $qn
     *  Quantile Coefficient
     *
     * @return float
     *  The p-Norm
     *
     * @see https://en.wikipedia.org/wiki/Norm_(mathematics)#p-norm
     * @see https://github.com/abscondment/statistics2/blob/master/lib/statistics2/base.rb#L89
     * @see http://seankross.com/notes/dpqr/#pnorm
     */
    protected function pNorm( $qn ) {

        $b = [
            1.570796288, 0.03706987906, -0.8364353589e-3, -0.2250947176e-3,
            0.6841218299e-5, 0.5824238515e-5, -0.104527497e-5, 0.8360937017e-7,
            -0.3231081277e-8, 0.3657763036e-10, 0.6936233982e-12
        ];

        if( $qn < 0.0 || $qn > 1.0 || $qn == 0.5 ) {
            return 0.0;
        }

        $w1 = $qn > 0.5 ? 1.0 - $qn : $qn;
        $w3 = -log( 4.0 * $w1 * ( 1.0 - $w1 ) );
        $w1 = $b[ 0 ];

        for( $i = 1; $i <= 10; $i++ ) {
            $w1 += $b[ $i ] * ( $w3 ** $i );
        }

        return $qn > 0.5 ? sqrt( $w1 * $w3 ) : -sqrt( $w1 * $w3 );
    }
}