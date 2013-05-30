<?php

namespace Next\DB\Table;

/**
 * Table Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Table {

    /**
     * Get Table Name
     */
    public function getTable();

    /**
     * List Table Fields
     */
    public function getFields();
}
