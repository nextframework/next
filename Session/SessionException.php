<?php

namespace Next\Session;

/**
 * Session Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class SessionException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x0000072C, 0x0000075E );

    /**
     * Session was already initialized
     *
     * @var integer
     */
    const ALREADY_INITIATED               = 0x0000072C;

    /**
     * Session could not be initialized
     *
     * @var integer
     */
    const INITIALIZATION_FAILURE          = 0x0000072C;

    // Exception Messages

    /**
     * Session was already initialized
     *
     * @return Next\Session\SessionException
     *  Exception for initialization impossibility
     */
    public static function alreadyInitiated() {

        return new self(

            'Session has been manually started with session_start()',

            self::ALREADY_INITIATED
        );
    }

    /**
     * Session could not be initialized
     *
     * @return Next\Session\SessionException
     *  Exception for initialization impossibility
     */
    public static function initializationFailure() {

        return new self(

            'Fail when starting Session',

            self::INITIALIZATION_FAILURE
        );
    }
}
