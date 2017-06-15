<?php

/**
 * Data Gateway Interface | DB\Table\DataGateway.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
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

    // Accessors

    /**
     * Get DataSource
     */
    public function getSource();

    /**
     * Get a copy of Data Source as array
     *
     * @return array
     *  Data Source as array
     */
    public function getArrayCopy();

    /**
     * Get Table Manager
     */
    public function getManager();
}