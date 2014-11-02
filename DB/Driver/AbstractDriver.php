<?php

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
abstract class AbstractDriver extends Object implements Parameterizable, Driver {

    /**
     * Connection Adapter Default Options
     *
     * @var array $defaultOptions
     */
    private $defaultOptions = array(

        'host'     => 'localhost',
        'username' => 'root',
        'password' => ''
    );

    /**
     * Connection Adapter Options
     *
     * @var Next\Components\Parameter $options
     */
    protected $options;

    /**
     * Connection Object
     *
     * @var mixed $connection
     */
    protected $connection;

    /**
     * Connection Driver Constructor
     *
     * @param mixed|optional $options
     *
     *   <br />
     *
     *   <p>
     *       List of Options to affect Database Drivers. Acceptable values are:
     *   </p>
     *
     *   <p>
     *
     *       <ul>
     *
     *           <li>Associative and multidimensional array</li>
     *
     *           <li>
     *
     *               An {@link http://php.net/manual/en/reserved.classes.php stdClass Object}
     *
     *           </li>
     *
     *           <li>A well formed Parameter Object</li>
     *
     *       </ul>
     *
     *   </p>
     *
     *   <p>The arguments taken in consideration are:</p>
     *
     *   <p>
     *
     *       <ul>
     *
     *           <li>
     *
     *               <p><strong>host</strong></p>
     *
     *               <p>The Database Host for connection</p>
     *
     *               <p>Default Value: <strong>localhost</strong></p>
     *
     *           </li>
     *
     *           <li>
     *
     *               <p><strong>username</strong></p>
     *
     *               <p>The Database User for connection</p>
     *
     *               <p>Default Value: <strong>root</strong>
     *
     *           </li>
     *
     *           <li>
     *
     *               <p><strong>password</strong></p>
     *
     *               <p>The Password used for connection</p>
     *
     *               <p>Default Value: <em><empty></em></p>
     *
     *           </li>
     *
     *       </ul>
     *
     *   </p>
     *
     *   <p>
     *       These are the arguments common to almost all Connection Drivers.
     *   </p>
     *
     *   <p>
     *       Other arguments taken in consideration are defined in (and by)
     *       concrete classes
     *   </p>
     *
     * @see Next\Components\Parameter
     */
    public function __construct( $options = NULL ) {

        // Setup Adapter Options

        $this -> options = new Parameter( $this -> defaultOptions, $options );

        // Check Requirements

        $this -> checkRequirements();

        // Check Adapter Integrity

        $this -> checkIntegrity();

        // Connecting

        //$this -> connect();
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
     * @return Next\Components\Parameter
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
     * Connection Adapter Extra initialization
     *
     * It's abstract because every driver must provide extensibility for its adapters
     */
    abstract protected function init();

    /**
     * Check for Driver-specific Requirements
     *
     * Must be implemented by every adapter the driver has, but not necessarily by the adapter itself
     */
    abstract protected function checkRequirements();
}
