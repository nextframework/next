<?php

namespace Next\Components\Events;

use Next\Components\Interfaces\Observer;    # Observer Interface
use Next\Components\Interfaces\Subject;     # Observer Subject Interface

use Next\Components\Object;                 # Object Class

/**
 * Event Handler Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2014 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Handler extends Object implements Subject {

    /**
     * Event Listeners (Observers)
     *
     * @var array $listeners
     */
    private $listeners = array();

    /**
     * The Event Object
     *
     * @var Next\Components\Events\Event $event
     */
    private $event;

    /**
     * Events Handler Constructor
     *
     * @param Next\Components\Events\Event|optional $event
     *  An optional Event Object
     */
    public function __construct( Event $event = NULL ) {

        parent::__construct();

        $this -> event = ( ! is_null( $event ) ? $event : new Event );
    }

    /**
     * Attach a new Event Listener Object
     *
     * @param Next\Components\Events\Listener $observer
     *  Observer to be attached as Event Listener
     *
     * @return Next\Components\Events\Handler
     *   Events Handler Object (Fluent Interface)
     *
     * @see Next\Components\Events\Handler::attach()
     */
    public function addListener( $name, Listener $listener ) {
        return $this -> attach( $listener, $name );
    }

    /**
     * Removes an Event Listener
     *
     * @param string $name
     *  Event Listener name to be searched and removed
     *
     * @return Next\Components\Events\Handler
     *   Events Handler Object (Fluent Interface)
     *
     * @see Next\Components\Events\Handler::detach()
     */
    public function removeListener( $name ) {
        return $this -> detach( new Listener, $name );
    }

    /**
     * Event Listener handling
     *
     * @param string $name
     *  Event Listener name
     *
     * @param  Next\Components\Events\Event|optional $event
     *  An optional Event Object
     *
     * @return Next\Components\Events\Handler
     *   Events Handler Object (Fluent Interface)
     *
     * @throws Next\Components\Events\EventsException
     *  Thrown if Event Listener cannot be find among added Event Listeners
     */
    public function handle( $name ) {

        if( ! array_key_exists( $name, $this -> listeners ) ) {
            throw EventsException::unknownListener( $name );
        }

        foreach( $this -> listeners[ $name ] as $listener ) {

            if( $this -> event -> isPropagationStopped() ) break;

            try {

                $listener -> setEvent( $this -> event ) -> update( $this, array_slice( func_get_args(), 1 ) );

            } catch( \ReflectionException $e ) {

                throw EventsException::listenerExecutionError( $name, $this -> event -> getName(), $e );
            }
        }

        return $this;
    }

    // Accessors

    /**
     * Get Event Handler Event Object
     *
     * @return Next\Components\Events\Event
     *  The Event Object
     */
    public function getEvent() {
        return $this -> event;
    }

    // Subject Interface Methods Implementation

    /**
     * Attach a new Observer to this Subject
     *
     * @param Next\Components\Interfaces\Observer $observer
     *  Observer to be attached as Event Listener
     *
     * @return Next\Components\Events\Handler
     *   Events Handler Object (Fluent Interface)
     *
     * @throws Next\Components\Events\EventsException
     *  Thrown if directly accessing the interface method instead of through
     *  Next\Components\Events\Handler::addListener(), case in which the second
     *  argument, part of Mediator Design Pattern, might not be set because it's
     *  not explicitly signed by this interface method
     *
     * @see Next\Components\Events\Handler::addListener()
     */
    public function attach( Observer $observer ) {

        if( count( func_get_args() ) != 2 ) {
            throw EventsException::disallowedManualListenerAttaching();
        }

        $this -> listeners[ func_get_arg( 1 ) ][] = $observer;

        return $this;
    }

    /**
     * Detach an Observer from this Subject
     *
     * @param Next\Components\Interfaces\Observer $observer
     *  Observer to be detached from Subject
     *
     * @return Next\Components\Events\Handler
     *   Events Handler Object (Fluent Interface)
     *
     * @throws Next\Components\Events\EventsException
     *  Thrown if directly accessing the interface method instead of through
     *  Next\Components\Events\Handler::removeListener(), case in which the second
     *  argument, part of Mediator Design Pattern, might not be set because it's
     *  not explicitly signed by this interface method
     *
     * @throws Next\Components\Events\EventsException
     *  Thrown if Event Name, coming from first argument of
     *  Next\Components\Events\Handler::removeHandler() cannot be find among added
     *  Event Listeners
     *
     * @see Next\Components\Events\Handler::removeListener()
     */
    public function detach( Observer $observer ) {

        if( count( func_get_args() ) != 2 ) {
            throw EventsException::disallowedManualListenerDettaching();
        }

        $listener = func_get_arg( 1 );

        if( ! array_key_exists( $listener, $this -> listeners ) ) {
            throw EventsException::unknownListener( $listener );
        }

        unset( $this -> listeners[ $listener ] );

        return $this;
    }

    /**
     * Notify all attached Observers about Subject changes
     *
     * @throws Next\Components\Debug\Exception
     *  Always thrown because Event Handler deal with Observers that act as
     *  Listeners which requires an Event Name to be triggered as designed
     *  by Mediator Design Pattern, and Next\Components\Interfaces\Subject::notify()
     *  doesn't allow this
     */
    public function notify() {
        throw EventsException::disallowedMethodUsage();
    }
}