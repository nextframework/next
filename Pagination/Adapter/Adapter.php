<?php

/**
 * Paginator Adapter Interface | Pagination\Adapter\Adapter.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Pagination\Adapter;

/**
 * Pagination Adapter Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Adapter extends \Countable {

    /**
     * Get items from given offset
     *
     * @param integer $offset
     *  Offset to start the range
     *
     * @param integer $itemsPerPage
     *  Number of Items per Page
     */
    public function getItems( $offset, $itemsPerPage );
}