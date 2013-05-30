<?php

namespace Next\DB\Table;

/**
 * DataGateway Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface DataGateway extends \Countable {

    // Data Gateway-related Methods

    /**
     * Update one or more records
     */
    public function update();

    /**
     * Delete one or more records
     */
    public function delete();

    // Accessors

    /**
     * Get DataSource
     */
    public function getSource();

    /**
     * Get Table Manager
     */
    public function getManager();
}