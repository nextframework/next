<?php

/**
 * Event Component Handler Class | Components\Events\Handler.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Events;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\BadFunctionCallException;

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
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'event' => [ 'type' => 'Next\Components\Events\Event', 'required' => TRUE ]
    ];

    /**
     * Event Listeners
     *
     * @var array $listeners
     */
    protected $listeners = [];

    /**
     * Event Listener handled results
     *
     * @var array $results
     */
    protected $results = [];

    /**
     * Adds a new Event Listener Object
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
     */
    public function removeListener( $name ) {

        if( array_key_exists( $name, $this -> listeners ) ) {
            unset( $this -> listeners[ $name ] );
        }

        return $this;
    }

    /**
     * Event Listener handling
     *
     * Additionally to the Event Listener trigger name an unlimited
     * number of arguments may be passed to the Listener.
     *
     * @param string $name
     *  Event Listener trigger name
     *
     * @throws \Next\Components\Events\EventsException
     *  Thrown if given Event Listener, as referenced by
     *  the trigger name, doesn't exist
     *
     * @throws \Next\Components\Events\EventsException
     *  Thrown if given Listener, as referenced by its trigger name,
     *  could not be handled, raising a ReflectionException
     */
    public function handle( $name ) {

        if( ! array_key_exists( $name, $this -> listeners ) ) return;

        foreach( $this -> listeners[ $name ] as $listener ) {

            if( $this -> options -> event -> isPropagationStopped() ) break;

            try {

                $result = $listener -> update(
                    $this -> options -> event, array_slice( func_get_args(), 1 )
                );

                $this -> results[ $name ][] = $result;

            } catch( \ReflectionException $e ) {

                throw new BadFunctionCallException(

                    sprintf(

                        'Event Listener <strong>%s</strong> of Event
                        <strong>%s</strong> could not be executed

                        The following error has been returned: %s',

                        $name, $event, $e -> getMessage()
                    )
                );
            }
        }
    }

    // Accessors

    /**
     * Get Event Handler Object
     *
     * @return \Next\Components\Events\Event
     *  The Event Object
     */
    public function getEvent() {
        return $this -> options -> event;
    }

    /**
     * Get results of handled Event Listeners
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

        return $this -> results;
    }
}