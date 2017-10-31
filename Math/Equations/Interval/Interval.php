<?php

/**
 * Math Interval Interface | Math\Equations\Interval\Interval.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Math\Equations\Interval;

use Next\Math\Equations\Equation;    # Equations Interface

/**
 * An Interface for all Mathematical Interval Algorithms
 *
 * @package    Next\Math
 *
 * @uses       Next\Math\Equations\Interval\Boundable
 *             Next\Math\Equations\Equation
 */
interface Interval extends Equation, Boundable {

    /**
     * Get total of Positive Results
     */
    public function getPositives() : int;

    /**
     * Get Total of Negative Results
     */
    public function getNegatives() : int;

    /**
     * Get Confidence Level
     */
    public function getConfidence() : float;

    /**
     * Get Total number of results
     */
    public function getTotal(): int;

    /**
     * Get Positive Fraction out of Total Results
     */
    public function getPositivesFraction(): int;

    /**
     * Get Quantile
     */
    public function getQuantile(): int;
}