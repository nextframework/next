<?php

namespace Next\Paginate\Style;

use Next\Paginate\Paginator;    # Paginator Class

/**
 * Standard Paginate Style Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Standard implements Style {

    /**
     * Number of elements displayed before Current Page
     *
     * @var integer $before
     */
    private $before = 5;

    /**
     * Number of elements displayed after Current Page
     *
     * @var integer $before
     */
    private $after = 5;

    /**
     * Set number of elements displayed before Current Page
     *
     * @param integer $amount
     *  Number of Pages displayed before current page
     *
     * @return Next\Paginate\Style\Style
     *  Paginate Style Object (Fluent Interface)
     */
    public function setBefore( $amount ) {

        $amount = (int) $amount;

        $this -> before = ( $amount < 1 ? 1 : $amount );

        return $this;
    }

    /**
     * Set number of items displayed after Current Page
     *
     * @param integer $amount
     *  Number of pages displayed after current page
     *
     * @return Next\Paginate\Style\Style
     *  Paginate Style Object (Fluent Interface)
     */
    public function setAfter( $amount ) {

        /**
         * @internal
         * Any value will be accepted here
         * If this value will be used, however, it's a different story
         */
        $this -> after = (int) $amount;

        return $this;
    }

    // Interface Method Implementation

    /**
     * Build Pages Range
     *
     * @param Next\Paginate\Paginator $paginator
     *  Paginator Object
     *
     * @return array
     *  Range of pages
     */
    public function buildRange( Paginator $paginator ) {

        $currentPage = $paginator -> getCurrentPage();
        $total       = count( $paginator );

        /**
         * Lower Bound
         *
         * Cannot be lower than one, otherwise we'll have negative pages
         */
        $lowerBound = ( $currentPage - $this -> before );
        $lowerBound = ( $lowerBound < 1 ? 1 : $lowerBound );

        /**
         * Upper Bound
         *
         * Cannot be higher than total of records, otherwise we'll have
         * offset errors
         */
        $upperBound = ( $currentPage + $this -> after );
        $upperBound = ( $upperBound > $total ? $total : $upperBound );

        return range( $lowerBound, $upperBound );
    }
}