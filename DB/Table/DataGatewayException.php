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
     * General violations on data access (or manipulation)
     *
     * @var integer
     */
    const NOTHING_TO_RETURN    = 0x000003C9;

    /**
     * Nothing to Update
     *
     * @var integer
     */
    const ACCESS_VIOLATION     = 0x000003CA;

    // Exception Messages

    /**
     * Exception for empty Data-sources being wrongly manipulated through Overloading
     *
     * @return Next\DB\Table\DataGatewayException
     *  Exception for empty Data-sources being wrongly manipulated through Overloading
     */
    public static function emptyDataSource() {

        return new self(

            'Data-source is empty and therefore cannot be directly manipulated',

            self::NOTHING_TO_RETURN
        );
    }

    /**
     * Trying to access a Row object directly when the RowSet has multiple records
     *
     * @return Next\DB\Table\DataGatewayException
     *  Exception for Data-sources with more than one Records being wrongly
     *  manipulated through Overloading
     */
    public static function accessViolation() {

        return new self(

            'Data-source has more than one record and therefore cannot be directly manipulated',

            self::ACCESS_VIOLATION
        );
    }
}