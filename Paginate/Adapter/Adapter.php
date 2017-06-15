<?php

/**
 * Paginator Adapter Interface | Paginate\Adapter\Adapter.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Paginate\Adapter;

/**
 * Paginate Adapter Interface
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