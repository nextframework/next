<?php

/**
 * Controllers Dispatcher Exception Class | Controller\Dispatcher\DispatcherException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Controller\Dispatcher;

/**
 * Defines wrapper static methods for all Exceptions thrown
 * within the Controller Dispatcher Module
 *
 * @package    Next\Controller\Dispatcher
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
     * @return \\Next\Controller\Dispatcher\DispatcherException
     *  Exception for caught ReflectionException
     */
    public static function reflection( \ReflectionException $e ) {

        return new self( $e -> getMessage(), self::REFLECTION );
    }
}
