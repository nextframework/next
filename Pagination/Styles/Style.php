<?php

/**
 * Pagination Styles Interface | Pagination\Style\Style.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Pagination\Styles;

use Next\Math\Equations\Interval\Boundable;    # Boundable Interval Interface

use Next\Pagination\Paginator;                 # Paginator Class

/**
 * An Interface for all Pagination Styles
 *
 * @package    Next\Pagination
 *
 * @uses       Next\Math\Equations\Interval\Boundable
 *             Next\Pagination\Paginator
 *             Countable
 */
interface Style extends Boundable, \Countable {

    /**
     * Set Paginator Object to read Pagination Informations from
     *
     * @param Next\Pagination\Paginator $paginator
     *  Paginator Object
     */
    public function setPaginator( Paginator $paginator );
}