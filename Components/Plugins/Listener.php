<?php

/**
 * Event Component Listener Class | Components\Plugins\Listener.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Plugins;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Validation\Verifiable;    # Verifiable Interface
use Next\Components\Object;        # Object Class

/**
 * Event Listeners acts similarly to Observer component of the Observer Pattern,
 * executing routines when the state of the associated Event changes
 *
 * @package    Next\Components\Plugins
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Validation\Verifiable
 *             Next\Components\Object
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
     * @param \Next\Components\Plugins\Event $event
     *  The Event being listened
     */
    public function update( Event $event ) {

        $args = func_get_args();

        // Removing the Event Handler to fix incoming structure

        array_shift( $args );

        if( (array) $this -> callback === $this -> callback ) {

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
     * @throws Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if the callback provided through Parameter Option
     *  is not a callable resource
     */
    public function verify() : void {

        if( ! is_callable( $this -> callback ) ) {

            throw new InvalidArgumentException(
                'The callback provided for Event Listener is not callable'
            );
        }
    }
}