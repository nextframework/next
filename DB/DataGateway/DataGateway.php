<?php

/**
 * Data Gateway Interface | DB\DataGateway\DataGateway.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\DataGateway;

/**
 * An Interface for all DataGateway Classes
 *
 * @package    Next\DB
 *
 * @uses       Countable
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