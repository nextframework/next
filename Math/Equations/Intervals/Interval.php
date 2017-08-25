<?php

namespace Next\Math\Equations\Intervals;

use Next\Components\Interfaces\Boundable;

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