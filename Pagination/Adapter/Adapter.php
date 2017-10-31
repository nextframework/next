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
 * An Interface for for all Pagination Adapters
 *
 * @package    Next\Pagination
 *
 * @uses       Countable
 */
interface Adapter extends \Countable {

    /**
     * Get items from given offset
     *
     * @param integer $offset
     *  Offset to start the range
     *
     * @param integer $visiblePages
     *  Number of Items per Page
     */
    public function getItems( $offset, $visiblePages ) : iterable;
}