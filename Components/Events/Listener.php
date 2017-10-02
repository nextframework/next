<?php

/**
 * Event Component Listener Class | Components\Events\Listener.php
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
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Interfaces\Verifiable;    # Verifiable Interface
use Next\Components\Object;                   # Object Class

/**
 * Events Listeners acts similarly to Observer component of the
 * Observer Pattern, executing routines when the
 * \Next\Components\Events\Event state changes
 *
 * @package    Next\Components\Events
 */
class Listener extends Object implements Verifiable {

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
     *  A callable resource for when an Event has been handled
     */
    public function __construct( $callback ) {

        $this -> callback = $callback;

        parent::__construct();
    }

    /**
     * Receives update from Subject
     *
     * @param \Next\Components\Events\event $event
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

                array_merge( [ $event ], array_shift( $args ) )
            );

        } else {

            $reflector = new \ReflectionFunction( $this -> callback );

            return $reflector -> invokeArgs(
                array_merge( [ $event ], array_shift( $args ) )
            );
        }
    }

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity
     *
     * @throws Next\Components\Events\EventsException
     *  Thrown if the callback provided through Parameter Option
     *  is not a callable resource
     */
    public function verify() {

        if( ! is_callable( $this -> callback ) ) {

            throw new InvalidArgumentException(
                'The callback provided for Event Listener is not callable'
            );
        }
    }
}