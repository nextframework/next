<?php

namespace Next\Controller\Router;

/**
 * Controller Router Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class RouterException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x00000165, 0x00000197 );

    /**
     * Database doesn't Exists
     *
     * @var integer
     */
    const DB_NOT_EXISTS             = 0x00000165;

    /**
     * Missing Required Parameter
     *
     * @var integer
     */
    const MISSING_REQUIRED_PARAM    = 0x00000166;

    /**
     * Invalid Required Parameter
     *
     * @var integer
     */
    const INVALID_REQUIRED_PARAM    = 0x00000167;

    // Exception Messages

    /**
     * Connection Failure
     *
     * The "Connection" (quoted because it's not always a true Connection)
     * could not be done by Router Adapter
     *
     * @param string $message
     *  Message to be thrown
     *
     * @param array|optional $args
     *  Variable list of argument to build final message
     *
     * @return Next\Controller\Router\RouterException
     *  Exception for Connection failure
     */
    public static function connectionFailure( $message, array $args = array() ) {

        return new self( $message, self::DB_NOT_EXISTS, $args );
    }

    /**
     * Missing Required Parameter
     *
     * A required Route Parameter is missing or mal-formed
     *
     * @param string $parameter
     *  Route Parameter being analyzed
     *
     * @return Next\Controller\Router\RouterException
     *  Exception for missing required parameter
     */
    public static function missingParameter( $parameter ) {

        return new self(

            'Missing or Mal-formed Required Parameter <strong>%s</strong>',

            self::MISSING_REQUIRED_PARAM,

            $parameter,

            // Bad Request

            400
        );
    }

    /**
     * Invalid Required Parameter
     *
     * A required Route Parameter has an invalid value
     *
     * @param string $parameter
     *  Route Parameter being analyzed
     *
     * @return Next\Controller\Router\RouterException
     *  Exception for invalid required parameter
     */
    public static function invalidParameter( $parameter ) {

        return new self(

            'Invalid Required Parameter <strong>%s</strong>',

            self::INVALID_REQUIRED_PARAM,

            $parameter,

            // Bad Request!

            400
        );
    }
}