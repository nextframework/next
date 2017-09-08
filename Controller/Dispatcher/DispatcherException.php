<?php

/**
 * Controllers Dispatcher Exception Class | Controller\Dispatcher\DispatcherException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
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
