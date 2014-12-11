<?php

namespace Next\Components\Events;

/**
 * Events Exception Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class EventsException extends \Next\Components\Debug\Exception {

    /**
     * Exception Codes Range
     *
     * @var array $range
     */
    protected $range = array( 0x00000231, 0x00000263 );

    /**
     * Disallowed method usage
     *
     * @var integer
     */
    const DISALLOWED_METHOD_USAGE    = 0x00000231;

    /**
     * Unknown Listener
     *
     * @var integer
     */
    const UNKNOWN_LISTENER           = 0x00000232;

    /**
     * Listener execution error
     *
     * @var integer
     */
    const LISTENER_EXECUTION_ERROR   = 0x00000233;

    /**
     * Disallowed method usage
     *
     * The Event Handler deal with Observers that act as Listeners which requires
     * an Event Name to be triggered as designed by Mediator Design Pattern, and
     * Next\Components\Interfaces\Subject::notify() doesn't allow this
     *
     * @param string|optional $reason
     *  An optional reason in case of more specific forbiddance
     *
     * @return Next\Components\Events\EventsException
     *  Disallowed method usage
     */
    public static function disallowedMethodUsage( $reason = NULL ) {

        return new self(

            '<p>
                The Event Handler deal with Observers acting as Listeners which requires an Event Name to trigger
            </p>

            %s',

            self::DISALLOWED_METHOD_USAGE, $reason
        );
    }

    /**
     * Disallowed manual Event Listener attaching
     *
     * An extension of Next\Components\Events\Event::disallowedMethodUsage()
     * with a specific reason referring to Next\Components\Events\Handler::attach()
     *
     * @return Next\Components\Events\EventsException
     *  Disallowed manual Event Listener attaching
     */
    public static function disallowedManualListenerAttaching() {

        return self::disallowedMethodUsage(

            '<p>
                Event Listeners cannot be added through implementation of
                <em>Next\Components\Interfaces\Subject::attach()</em>
            </p>

            <p>
                Use <em>Next\Components\Events\Handler::addListener()</em> instead
            </p>'
        );
    }

    /**
     * Disallowed manual Event Listener detaching
     *
     * An extension of Next\Components\Events\Event::disallowedMethodUsage()
     * with a specific reason referring to Next\Components\Events\Handler::attach()
     *
     * @return Next\Components\Events\EventsException
     *  Disallowed manual Event Listener detaching
     */
    public static function disallowedManualListenerDetaching() {

        return self::disallowedMethodUsage(

            '<p>
                Event Listeners cannot be removed through implementation of
                <em>Next\Components\Interfaces\Subject::detach()</em>
            </p>

            <p>
                Use <em>Next\Components\Events\Handler::removeListener()</em> instead
            </p>'
        );
    }

    /**
     * Unknown Event Listener
     *
     * @param string $listener
     *  Listener trying to be handled
     *
     * @return Next\Components\Events\EventsException
     *  Unknown Event Listener
     */
    public static function unknownListener( $listener ) {
        return new self( 'Unknown Event Listener <strong>%s</strong>', self::UNKNOWN_LISTENER, $listener );
    }

    /**
     * Event Listener Execution Error
     *
     * @param  string $listener
     *  Event Listener name
     *
     * @param  string $event
     *  Event name
     *
     * @param  \ReflectionException $e
     *  ReflectionException caught while trying to execute the Listener
     *
     * @return Next\Components\Events\EventsException
     *  Event Listener Execution Error
     */
    public static function listenerExecutionError( $listener, $event, \ReflectionException $e ) {

        return new self(

            '<p>
                Event Listener <strong>%s</strong> of Event <strong>%s</strong> could not be executed
            </p>

            <p>
                The following error was returned: %s
            </p>',

            self::LISTENER_EXECUTION_ERROR, array( $listener, $event, $e -> getMessage() )
        );
    }
}