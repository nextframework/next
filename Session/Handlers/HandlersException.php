<?php

/**
 * Sessions handler Exception Class | Session\Handlers\HandlersException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Session\Handlers;

/**
 * Session Handlers Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class HandlersException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x000006F9, 0x0000072B );

    /**
     * Unknown Session Handler
     *
     * @var integer
     */
    const UNKNOWN_HANDLER = 0x000006F9;

    // Exception Messages

    /**
     * Unknown Session Handler
     *
     * @param string $handlerName
     *  Desired Handler Name
     *
     * @return \Next\Session\Handlers\HandlersException
     *  Exception for unknown handler
     */
    public static function unknownHandler( $handlerName ) {

        return new self(

            'None of the registered Session Handlers matches your choice for <strong>%s</strong>.

            <br />

            Please use a true Handler Object or check if desired Handler Name matches one of the previoulsy assigned Handlers',

            self::UNKNOWN_HANDLER,

            $handlerName
        );
    }
}
