<?php

namespace Next\Components\Events;

use Next\Components\Interfaces\Observer;    # Observer Interface
use Next\Components\Interfaces\Subject;     # Observer Subject Interface

use Next\Components\Object;                 # Object Class

/**
 * Listener Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2014 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Listener extends Object implements Observer {

    /**
     * The Event Object
     *
     * @var Next\Components\Events\Event $event
     */
    protected $event;

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
     *  A callable resource for Next\Components\Interfaces\Observer::update()
     *  implementation in Next\Components\Events\Listener::update()
     */
    public function __construct( $callback ) {

        parent::__construct();

        $this -> callback = $callback;
    }

    /**
     * Set the Event Object
     *
     * @param Next\Components\Events\Event $event
     *  The Event Object
     */
    public function setEvent( Event $event ) {

        $this -> event = $event;

        return $this;
    }

    /**
     * Receives update from Subject
     *
     * @param Next\Components\Interfaces\Subject $subject
     *  The Subject notifying the observer of an update
     */
    public function update( Subject $subject ) {

        $reflector = new \ReflectionFunction( $this -> callback );

        $args = func_get_args();

        // Removing the Event Handler to fix incoming structure

        array_shift( $args );

        return $reflector -> invokeArgs( array_merge( array( $this -> event ), array_shift( $args ) ) );
    }
}