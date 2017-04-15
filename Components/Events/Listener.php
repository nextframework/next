<?php

namespace Next\Components\Events;

use Next\Components\Object;                 # Object Class

/**
 * Listener Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2014 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Listener extends Object {

    /**
     * Listener callback
     *
     * @var callable $callback
     */
    protected $callback;

    /**
     * Listener Constructor
     *
     * @param callable $callback
     *  A callable resource for when an Event has been handled,
     *  much like Next\Components\Interfaces\Observer::update()
     *
     * @param mixed|Next\Components\Object|Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for each Event Listener
     *
     * @throws Next\Components\Events\EventsException
     *  Thrown if provided callback is not callable
     *
     * @see Next\Components\Interfaces\Observer::update()
     */
    public function __construct( $callback, $options = NULL ) {

        parent::__construct( $options );

        if( ! is_callable( $callback ) ) {
            throw EventsException::invalidCallback();
        }

        $this -> callback = $callback;
    }

    /**
     * Receives update from Subject
     *
     * @param Next\Components\Events\event $event
     *  The Event being listened
     */
    public function update( Event $event ) {

        $args = func_get_args();

        // Removing the Event Handler to fix incoming structure

        array_shift( $args );

        if( is_array( $this -> callback ) ) {

            $reflector = new \ReflectionMethod(
                $this -> callback[ 0 ], $this -> callback[ 1 ]
            );

            return $reflector -> invokeArgs(

                $this -> callback[ 0 ],

                array_merge( array( $event ), array_shift( $args ) )
            );

        } else {

            $reflector = new \ReflectionFunction( $this -> callback );

            return $reflector -> invokeArgs(
                array_merge( array( $event ), array_shift( $args ) )
            );
        }
    }
}