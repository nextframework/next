<?php

namespace Next\Controller\Dispatcher;

/**
 * Controller Dispatcher Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class DispatcherException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x00000132, 0x00000164 );

    /**
     * ReflectionException caught
     *
     * Usually 'method not found' related, but anything reported
     * through by this class can be framed here
     *
     * @var integer
     */
    const REFLECTION = 0x00000132;

    // Exception Messages

    /**
     * ReflectionException caught
     *
     * @param \ReflectionException $e
     *  RflectionException caught
     *
     * @return \Next\Controller\Dispatcher\DispatcherException
     *  Exception for caught ReflectionException
     */
    public static function reflection( \ReflectionException $e ) {

        return new self( $e -> getMessage(), self::REFLECTION );
    }
}
