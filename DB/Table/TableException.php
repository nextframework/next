<?php

/**
 * Database Table Exception Exception Class | DB\Table\TableException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
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
     * Missing PRIMARY KEY
     *
     * @var integer
     */
    const MISSING_PK              = 0x00000396;

    /**
     * Nothing to Update
     *
     * @var integer
     */
    const NOTHING_TO_INSERT       = 0x00000397;

    /**
     * Nothing to Update
     *
     * @var integer
     */
    const NOTHING_TO_UPDATE       = 0x00000398;

    /**
     * Statement Preparing
     *
     * @var integer
     */
    const PREPARE                 = 0x00000399;

    /**
     * Statement Executing
     *
     * @var integer
     */
    const EXECUTE                 = 0x0000039A;

    /**
     * General violations on data access (or manipulation)
     *
     * @var integer
     */
    const ACCESS_VIOLATION        = 0X0000039B;

    // Exception Messages

    /**
     * Missing PRIMARY KEY definition
     *
     * @param string $table
     *  Table name
     *
     * @return \Next\DB\Table\TableException
     *  Exception to when the PRIMARY KEY column has not been defined in Table Class
     */
    public static function missingPrimaryKey( $table ) {

        return new self(

            'PRIMARY KEY has not been defined for Table Class <strong>%s</strong> (<em>%s</em>)',

            self::MISSING_PK, array( (string) $table, $table -> getClass() -> getName() )
        );
    }

    /**
     * Unable to insert new record
     *
     * @return \Next\DB\Table\TableException
     *  Exception when there is nothing to insert
     */
    public static function nothingToInsert() {
        return new self( 'Nothing to insert', self::NOTHING_TO_INSERT );
    }

   /**
     * Unable to update an existent record
     *
     * @return \Next\DB\Table\TableException
     *  Exception when there is nothing to update
     */
    public static function nothingToUpdate() {
        return new self( 'Nothing to update', self::NOTHING_TO_UPDATE );
    }

    /**
     * Unable to prepare statement
     *
     * @param \Next\DB\Driver\DriverException $e
     *  DriverException caught while invoking
     *  \Next\DB\Driver\Driver::prepare()
     *
     * @return \Next\DB\Table\TableException
     *  Exception for statement preparing failure
     */
    public static function prepare( DriverException $e ) {
        return new self( $e -> getMessage(), self::PREPARE );
    }

    /**
     * Unable to execute statement
     *
     * @param \Next\DB\Statement\StatementException $e
     *  StatementException caught while invoking
     *  \Next\DB\Statement\Statement::execute()
     *
     * @return \Next\DB\Table\TableException
     *  Exception for statement execution failure
     */
    public static function execute( StatementException $e ) {
        return new self( $e -> getMessage(), self::EXECUTE );
    }
}