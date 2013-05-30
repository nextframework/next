<?php

namespace Next\Paginate\Style;

use Next\Paginate\Paginator;    # Paginator Class

/**
 * Sliding Paginate Style Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Sliding implements Style {

    // Interface Method Implementation

    /**
     * Build Pages Range
     *
     * @param Next\Paginate\Paginator $paginator
     *   Paginator Object
     *
     * @return array
     *   Range of pages
     */
    public function buildRange( Paginator $paginator ) {

        $itemsPerPage   = $paginator -> getItemsPerPage();
        $currentPage    = $paginator -> getCurrentPage();
        $total          = count( $paginator );

        if( $itemsPerPage > $total ) {

            $itemsPerPage = $total;
        }

        $delta = ceil( $itemsPerPage / 2 );

        if( ( $currentPage - $delta ) > ( $total - $itemsPerPage ) ) {

            $lowerBound = $total - $itemsPerPage + 1;
            $upperBound = $total;

        } else {

            // We're getting close to end, let's change a little bit

            if( ( $currentPage - $delta ) < 0 ) {
                $delta = $currentPage;
            }

            $offset = ( $currentPage - $delta );

            $lowerBound = $offset + 1;
            $upperBound = $offset + $itemsPerPage;
        }

        return range( $lowerBound, $upperBound );
    }
}