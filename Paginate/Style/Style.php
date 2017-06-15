<?php

/**
 * Pagination Styles Interface | Paginate\Style\Style.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Paginate\Style;

use Next\Paginate\Paginator;    # Paginator Class

/**
 * Paginate Style Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Style {

    /**
     * Build Pages Range
     *
     * @param \Next\Paginate\Paginator $paginator
     *  Paginator Object
     */
    public function buildRange( Paginator $paginator );
}