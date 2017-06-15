<?php

/**
 * Events Components Exception Class | Components\Events\EventsException.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Components\Events;

/**
 * Defines wrapper static methods for all Exceptions thrown
 * within the Events Component
 *
 * @package    Next\Components\Events
 */
class EventsException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x00000231, 0x00000263 );

    /**
     * Unknown Listener
     *
     * @var integer
     */
    const UNKNOWN_LISTENER           = 0x00000231;

    /**
     * Listener execution error
     *
     * @var integer
     */
    const LISTENER_EXECUTION_ERROR   = 0x00000232;

    /**
     * Invalid Listener Callback
     *
     * @var integer
     */
    const INVALID_LISTENER_CALLBACK  = 0x00000233;

    /**
     * Unknown Event Listener
     *
     * @param string $name
     *  Event Listener trigger reference
     *
     * @return \Next\Components\Events\EventsException
     *  Unknown Event Listener
     */
    public static function unknownListener( $name ) {

        return new self(

            'Unknown Event Listener referenced by trigger name <strong>%s</strong>',

            self::UNKNOWN_LISTENER, $name
        );
    }

    /**
     * Event Listener Execution Error
     *
     * @param string $name
     *  Event Listener trigger reference
     *
     * @param string $event
     *  Event name
     *
     * @param \ReflectionException $e
     *  ReflectionException caught while trying to execute the Listener
     *
     * @return \Next\Components\Events\EventsException
     *  Event Listener Execution Error
     */
    public static function listenerExecutionError( $name, $event, \ReflectionException $e ) {

        return new self(

            'Event Listener <strong>%s</strong> of Event <strong>%s</strong>
            could not be executed

            The following error was returned: %s',

            self::LISTENER_EXECUTION_ERROR, array( $name, $event, $e -> getMessage() )
        );
    }

    /**
     * Invalid Listener Callback
     *
     * @return \Next\Components\Events\EventsException
     *  Invalid Listener Callback error
     */
    public static function invalidCallback() {

        return new self(

            'The callback defined for Event Listener is not callable',

            self::INVALID_LISTENER_CALLBACK
        );
    }
}