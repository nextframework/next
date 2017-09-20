<?php

/**
 * Database Gateway Exception Class | DB\Table\DataGatewayException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
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
     * Nothing to Update
     *
     * @var integer
     */
    const ACCESS_VIOLATION     = 0x000003C9;

    // Exception Messages

    /**
     * Trying to access a Row object directly when the RowSet has multiple records
     *
     * @return \Next\DB\Table\DataGatewayException
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