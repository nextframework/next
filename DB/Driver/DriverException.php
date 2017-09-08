<?php

/**
 * Database Driver Exception Class | DB\Driver\DriverException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\Driver;

/**
 * Connection Driver Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class DriverException extends \Next\Components\Debug\Exception {

    /**
     * PDOException
     *
     * @var integer
     */
    const PDOEXCEPTION = 0x00000297;

    /**
     * Missing Connection Parameter
     *
     * @var integer
     */
    const MISSING_CONNECTION_PARAMETER = 0x00000298;

    // Exception Messages

    /**
     * PDOException Caught
     *
     * @param PDOException $e
     *  PDOException caught
     *
     * @return \Next\DB\Driver\DriverException
     *  Exception for a caught PDOException
     */
    public static function PDOException( \PDOException $e ) {

        return new self( $e -> getMessage(), self::PDOEXCEPTION );
    }

    /**
     * Missing Connection Parameter
     *
     * @param string $message
     *  Message to be thrown
     *
     * @return \Next\DB\Driver\DriverException
     *  Exception for missing Connection Adapter
     */
    public static function missingConnectionAdapterParameter( $message ) {

        return new self( $message, self::MISSING_CONNECTION_PARAMETER );
    }
}