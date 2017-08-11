<?php

/**
 * Database Query Exception Class | DB\Query\QueryException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\Query;

/**
 * Query Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class QueryException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x000002FD, 0x0000032F );

    /**
     * General violations on data access (or manipulation)
     *
     * @var integer
     */
    const MULTIPLE_CONDITIONS = 0x000002FD;

    // Exception Messages

    /**
     * Exception for when multiple conditions are being passed to a Query Builder method
     *
     * @param string $where
     *  An hint of where the violation occurred
     *
     * @return \Next\DB\Table\DataGatewayException
     *  Exception for when multiple conditions are being passed to a Query Builder method
     */
    public static function multipleConditions( $where ) {

        return new self(

            'Multiple conditions are not allowed (Builder::%s)',

            self::MULTIPLE_CONDITIONS,

            (string) $where
        );
    }
}