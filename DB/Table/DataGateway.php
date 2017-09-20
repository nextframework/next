<?php

/**
 * Data Gateway Interface | DB\Table\DataGateway.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
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

    /**
     * Get DataSource
     */
    public function getSource();

    /**
     * Get a copy of Data-source as array
     *
     * @return array
     *  Data-source as array
     */
    public function getArrayCopy();
}