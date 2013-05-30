<?php

namespace Next\Paginate\Style;

use Next\Paginate\Paginator;    # Paginator Class

/**
 * All Elements Paginate Style Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class All implements Style {

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
        return range( 1, count( $paginator ) );
    }
}