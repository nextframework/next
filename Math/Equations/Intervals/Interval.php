<?php

/**
 * HTTP Request Class | HTTP\Request.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Math\Equations\Intervals;

use Next\Components\Interfaces\Boundable;    # Boundable Interface

/**
 * The Interval Type describes the implementation of a
 * Mathematical Interval Algorithm defining all publicly available methods
 *
 * @package    Next\Math
 *
 * @uses       \Next\Components\Interfaces\Boundable
 */
interface Interval extends Boundable {

    /**
     * Get total of Positive Results
     */
    public function getPositives();

    /**
     * Get Total of Negative Results
     */
    public function getNegatives();

    /**
     * Get Confidence Level
     */
    public function getConfidence();

    /**
     * Get Total number of results
     */
    public function getTotal();

    /**
     * Get Positive Fraction out of Total Results
     */
    public function getPositivesFraction();

    /**
     * Get Quantile
     */
    public function getQuantile();
}