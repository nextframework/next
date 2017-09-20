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

use Next\Components\Interfaces\Boundable;    # Boundable Interface
use Next\Pagination\Paginator;

/**
 * Pagination Style Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
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