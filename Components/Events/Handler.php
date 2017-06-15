<?php

/**
 * Event Component Handler Class | Components\Events\Handler.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Components\Events;

use Next\Components\Object;               # Object Class
use Next\Components\Collections\Lists;    # Collection Lists Class

/**
 * The Event Handler deals with all \Next\Components\Events\Listener
 * associated with an \Next\Components\Events\Event
 *
 * @package    Next\Components\Events
 */
class Handler extends Object {

    /**
     * Event Listeners
     *
     * @var array $listeners
     */
    protected $listeners = array();

    /**
     * The Event Object
     *
     * @var \Next\Components\Events\Event $event
     */
    protected $event;

    /**
     * Event Listener handled results
     *
     * @var array $results
     */
    protected $results = array();

    /**
     * Events Handler Constructor
     *
     * @param \Next\Components\Events\Event|optional $event
     *  An optional Event Object
     *
     * @param mixed|\Next\Components\Object|\Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for the Event Handler
     */
    public function __construct( Event $event = NULL, $options = NULL ) {

        parent::__construct( $options );

        $this -> event = ( ! is_null( $event ) ? $event : new Event );
    }

    /**
     * Attach a new Event Listener Object
     *
     * @param string $name
     *  Event Listener identifier key
     *
     * @param \Next\Components\Events\Listener $listener
     *  Event Listener to be handled within the Event
     *
     * @return \Next\Components\Events\Handler
     *   Events Handler Object (Fluent Interface)
     */
    public function addListener( $name, Listener $listener ) {

        $this -> listeners[ $name ][] = $listener;

        return $this;
    }

    /**
     * Removes an Event Listener
     *
     * @param string $name
     *  Event Listener trigger reference, through which it'll be searched and removed
     *
     * @return \Next\Components\Events\Handler
     *   Events Handler Object (Fluent Interface)
     *
     * @throws \Next\Components\Events\EvenstException
     *  Thrown if given Listener, as referenced by its trigger name, doesn't exist
     */
    public function removeListener( $name ) {

        if( ! array_key_exists( $name, $this -> listeners ) ) {
            throw EventsException::unknownListener( $name );
        }

        unset( $this -> listeners[ $name ] );

        return $this;
    }

    /**
     * Event Listener handling
     *
     * Additionally to the trigger name an unlimited number of arguments
     * may be passed to the Listener.
     *
     * This possible list is not explicitly documented in order
     * to not create an unused variable just for the documentation
     *
     * @param string $name
     *  Event Listener trigger name
     *
     * @return \Next\Components\Events\Handler
     *   Events Handler Object (Fluent Interface)
     *
     * @throws \Next\Components\Events\EventsException
     *  Throw if given Event Listener, as referenced by
     *  the trigger name, doesn't exist
     *
     * @throws \Next\Components\Events\EventsException
     *  Thrown if given Listener, as referenced by its trigger name,
     *  could not be handled, raising a ReflectionException
     */
    public function handle( $name ) {

        if( ! array_key_exists( $name, $this -> listeners ) ) {
            throw EventsException::unknownListener( $name );
        }

        foreach( $this -> listeners[ $name ] as $listener ) {

            if( $this -> event -> isPropagationStopped() ) break;

            try {

                $result = $listener -> update(
                    $this -> event, array_slice( func_get_args(), 1 )
                );

                if( ! array_key_exists( $name, $this -> results ) ) {
                    $this -> results[ $name ] = new Lists;
                }

                $this -> results[ $name ] -> add( $result );

            } catch( \ReflectionException $e ) {

                throw EventsException::listenerExecutionError(
                    $name, $this -> event -> getName(), $e
                );
            }
        }

        return $this;
    }

    // Accessors

    /**
     * Get Event Handler Object
     *
     * Allow an automatically assigned Event (empty constructor) to
     * have its propagation directives manipulated
     *
     * @return \Next\Components\Events\Event
     *  The Event Object
     */
    public function getEvent() {
        return $this -> event;
    }

    /**
     * Get results of Event Listeners handled
     *
     * @param string $name
     *  An optional Event Listener trigger name to filter to a specific Collection
     *
     * @return array|\Next\Components\Collections\Lists
     *  If <strong$name</strong> is provided and matches to a Collection
     *  of handled Listeners the Collection, as instance of
     *  \Next\Components\Collections\Lists will be returned
     *
     * Otherwise an array with all handled results will instead
     */
    public function getHandledResults( $name = NULL ) {

        if( array_key_exists( $name, $this -> results ) ) {
            return $this -> results[ $name ];
        }

        return $this -> result;
    }
}