<?php

/**
 * pNorm Algorithm Prototypable Class | Math\Prototypes\pNorm.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace  Next\Math\Prototypes;

use Next\Components\Interfaces\Prototypable;    # Prototypable Interface
use Next\Math\Floats;                           # Floats Object Class

/**
 * Computes the inverse of normal distribution (p-Norm) returning the integral
 * from `\(-\infty\)` to \(q\) of the PDF (Probability Density Function)
 * of the normal distribution
 *
 * @package    Next\Math
 */
class pNorm implements Prototypable {

    // Prototypable Interface Method Implementation

    /**
     * Prototypes the pNorm routine by proxying, treating and handling
     * the mixed arguments received
     *
     * @return \Next\Math\Floats
     *  A Floats Object with the pNorm algorithm results
     */
    public function prototype() : Strings {

        list( $quantile ) = func_get_arg( 0 ) + [ 0.0 ];

        return new Floats( [ 'value' => $this -> pNorm( $quantile ) ] );
    }

    /**
     * The pNorm Algorithm routine
     *
     * @author    Shin-ichiro HARA <sinara@blade.nagaokaut.ac.jp>
     * @author    Brendan Ribera <brendan.ribera@gmail.com>
     *
     * @link      https://github.com/abscondment/statistics2/blob/master/lib/statistics2/base.rb#L89
     *
     * @param float $quantile
     *  Quantile Coefficient
     *
     * @return float
     *  The inverse of normal distribution computed for given Quantile
     *
     * @see https://en.wikipedia.org/wiki/Norm_(mathematics)#p-norm
     * @see http://seankross.com/notes/dpqr/#pnorm
     */
    private function encode( float $quantile ) : float {

        $b = [
            1.570796288, 0.03706987906, -0.8364353589e-3, -0.2250947176e-3,
            0.6841218299e-5, 0.5824238515e-5, -0.104527497e-5, 0.8360937017e-7,
            -0.3231081277e-8, 0.3657763036e-10, 0.6936233982e-12
        ];

        if( $quantile < 0.0 || $quantile > 1.0 || $quantile == 0.5 ) {
            return 0.0;
        }

        $w1 = $quantile > 0.5 ? 1.0 - $quantile : $quantile;
        $w3 = -log( 4.0 * $w1 * ( 1.0 - $w1 ) );
        $w1 = $b[ 0 ];

        for( $i = 1; $i <= 10; $i++ ) {
            $w1 += $b[ $i ] * ( $w3 ** $i );
        }

        return $quantile > 0.5 ? sqrt( $w1 * $w3 ) : -sqrt( $w1 * $w3 );
    }
}