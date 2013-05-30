<?php

namespace Next\DB\Table;

use Next\DB\Driver\DriverException;          # Driver Exception Class
use Next\DB\Statement\StatementException;    # Statement Exception Class

/**
 * Table Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class TableException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x00000396, 0x000003C8 );

    /**
     * Statement Preparing
     *
     * @var integer
     */
    const PREPARE = 0x00000396;

    /**
     * Statement Executing
     *
     * @var integer
     */
    const EXECUTE = 0x00000397;

    // Exception Messages

    /**
     * Unable to prepare statement
     *
     * @param Next\DB\Driver\DriverException $e
     *   DriverException caught while invoking
     *   Next\DB\Driver\Driver::prepare()
     *
     * @return Next\DB\Table\TableException
     *   Exception for statement preparing failure
     */
    public static function prepare( DriverException $e ) {
    	return new self( $e -> getMessage(), self::PREPARE );
    }

    /**
     * Unable to execute statement
     *
     * @param Next\DB\Statement\StatementException $e
     *   StatementException caught while invoking
     *   Next\DB\Statement\Statement::execute()
     *
     * @return Next\DB\Table\TableException
     *   Exception for statement execution failure
     */
    public static function execute( StatementException $e ) {
    	return new self( $e -> getMessage(), self::EXECUTE );
    }
}