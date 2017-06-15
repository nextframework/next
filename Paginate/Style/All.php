<?php

/**
 * Pagination Style Class: All | Paginate\Style\All.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
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
     * @param \Next\Paginate\Paginator $paginator
     *  Paginator Object
     *
     * @return array
     *  Range of pages
     */
    public function buildRange( Paginator $paginator ) {
        return range( 1, count( $paginator ) );
    }
}