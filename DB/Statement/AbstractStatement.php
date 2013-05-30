<?php

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
     * @var Next\DB\Driver\Driver $driver
     */
    protected $driver;

    /**
     * Statement Constructor.
     *
     * @param Next\DB\Driver\Driver $driver
     *   Connection Driver
     */
    public function __construct( Driver $driver ) {

        $this -> driver =& $driver;
    }
}