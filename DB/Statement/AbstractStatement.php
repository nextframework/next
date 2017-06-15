<?php

/**
 * Database Statement Abstract Class | DB\Statement\AbstractStatement.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\DB\Statement;

use Next\DB\Driver\Driver;      # Connection Driver Interface
use Next\Components\Object;     # Object Class

/**
 * Statement Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractStatement extends Object implements Statement {

    /**
     * Connection Object
     *
     * @var \Next\DB\Driver\Driver $driver
     */
    protected $driver;

    /**
     * Statement Constructor.
     *
     * @param \Next\DB\Driver\Driver $driver
     *  Connection Driver
     *
     * @param mixed|\Next\Components\Object|\Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for each Statement Adapter
     */
    public function __construct( Driver $driver, $options = NULL ) {

        parent::__construct( $options );

        $this -> driver =& $driver;
    }
}