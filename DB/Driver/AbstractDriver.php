<?php

/**
 * Database Abstract Driver Class | DB\Driver\AbstractDriver.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\Driver;

use Next\Components\Interfaces\Parameterizable;    # Parameterizable Interface
use Next\Components\Object;                        # Object Class
use Next\Components\Parameter;                     # Parameter Class

/**
 * Connection Driver Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractDriver extends Object implements Driver {

    /**
     * Connection Adapter Default Options
     *
     * @var array $defaultOptions
     */
    protected $defaultOptions = [

        'host'     => 'localhost',
        'username' => 'root',
        'password' => '',
    ];

    /**
     * Connection Object
     *
     * @var mixed $connection
     */
    protected $connection;

    /**
     * Additional Initialization
     * Checks Driver requirements and Driver overall integrity
     */
    protected function init() {

        // Check Requirements

        $this -> checkRequirements();

        // Check Adapter Integrity

        $this -> checkIntegrity();
    }

    // Accessors

    /**
     * Get Connection
     *
     * @return mixed
     *  Connection Object
     */
    public function getConnection() {

        // Connecting if needed

        if( ! $this -> isConnected() ) {
            $this -> connect();
        }

        return $this -> connection;
    }

    // Parameterizable Interface Methods Implementation

    /**
     * Set DB Adapter Options
     *
     * Not required for now, but overwritable!
     */
    public function setOptions() {}

    /**
     * Get DB Driver Options
     *
     * @return \Next\Components\Parameter
     *  Parameter Object with merged options
     */
    public function getOptions() {
        return $this -> options;
    }

    // Auxiliary Methods

    /**
     * Check Driver Integrity
     *
     * Empty by default, concrete Adapters should overwrite it
     */
    private function checkIntegrity() {}

    // Abstract Methods Definition

    /**
     * Connection Adapter Configuration
     *
     * It's abstract because every driver must provide extensibility for its adapters
     */
    abstract protected function configure();

    /**
     * Check for Driver-specific Requirements
     *
     * Must be implemented by every adapter the driver has, but not necessarily by the adapter itself
     */
    abstract protected function checkRequirements();
}
