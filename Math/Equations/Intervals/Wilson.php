<?php

namespace Next\Math\Equations\Intervals;

class Wilson extends AbstractInterval {

    // Boundable Interface Methods Implementation

    /**
     * Get the Lower Bound of a Subset
     *
     * @return integer|float
     *  The Lower Bound
     */
    public function getLowerBound() {

        if( $this -> n == 0 ) return 0;

        return ( $this -> left() - $this -> inner() ) / $this -> right();
    }

    /**
     * Get the Upper Bound of a Subset
     *
     * @return integer|float
     *  The Upper Bound
     */
    public function getUpperBound() {

        if( $this -> n == 0 ) return 0;

        return ( $this -> left() + $this -> inner() ) / $this -> right();
    }

    // Auxiliary Methods

    /**
     * Wrapper method for the leftmost part of Wilson's Interval Equation
     * used by both Upper and Lower Bounds
     *
     * @return float
     *  Left part of Equation
     */
    private function left() {
        return $this -> p + ( $this -> z ** 2 / ( 2 * $this -> n ) );
    }

    /**
     * Wrapper method for the innermost part of Wilson's Interval Equation
     * used by both Upper and Lower Bounds
     *
     * @return float
     *  Inner part of Equation
     */
    private function inner() {

        $sqrt = sqrt(
            ( $this ->  p * ( 1 - $this -> p ) + $this -> z ** 2 / ( 4 * $this -> n ) ) / $this -> n
        );

        return $this -> z * $sqrt;
    }

    /**
     * Wrapper method for the rightmost part of Wilson's Interval Equation
     * used by both Upper and Lower Bounds
     *
     * @return float
     *  Right part of Equation
     */
    private function right() {
        return 1 + ( $this -> z ** 2 / $this -> n );
    }
}