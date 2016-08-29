<?php

namespace Next\DB\Table;

/**
 * DataGateway Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2014 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class DataGatewayException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x000003C9, 0x000003FB );

    /**
     * Nothing to Update
     *
     * @var integer
     */
    const ACCESS_VIOLATION = 0x000003C9;

    // Exception Messages

    /**
     * Trying to access a Row object directly when the RowSet has multiple records
     *
     * @return Next\DB\Table\TableException
     *  Exception when a RowSet object is accessed while having multiple records
     */
    public static function accessViolation() {

        return new self(

            'Direct manipulation of a RowSet is allowed only when it has only one record',

            self::ACCESS_VIOLATION
        );
    }
}