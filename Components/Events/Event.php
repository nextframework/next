<?php

namespace Next\Components\Events;

use Next\Components\Object;    # Object Class

/**
 * Event Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2014 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Event extends Object {

    /**
     * Flag to condition whether no further event listeners should be triggered
     *
     * @var bool $propagationStopped
     */
    protected $propagationStopped = FALSE;

    /**
     * Event Name
     *
     * @var string $name
     */
    protected $name;

    /**
     * Event Constructor
     *
     * @param string|optional $name
     *  An optional Event Name
     */
    public function __construct( $name = NULL ) {

        parent::__construct();

        $this -> name = ( ! is_null( $name ) ? $name : 'Next' );
    }

    /**
     * Returns whether or nnot further Event Listeners should be triggered.
     *
     * @return bool
     *   Whether propagation was already stopped for this event
     *
     * @see Next\Components\Events\Event::stopPropagation()
     */
    public function isPropagationStopped() {
        return $this -> propagationStopped;
    }

    /**
     * Starts (or restarts) the propagation of the Event to further Event Listeners.
     *
     * If multiple Event listeners are connected to the same event, and one of
     * Event Listeners stopped the propagation, this allow sequential Listeners
     * to be fired again
     *
     * This is useful if an Event Handler responsible for a critical error is fired
     * and stopped the propagation, and a complementary, but not crucial, task also
     * failed and needs to inform the endpoint too
     *
     * @return void
     */
    public function startPropagation() {
        $this -> propagationStopped = FALSE;
    }

    /**
     * Stops the propagation of the Event to further Event Listeners.
     *
     * If multiple Event listeners are connected to the same event,
     * further Event Listeners will not be triggered once any trigger calls
     * this method
     *
     * @return void
     */
    public function stopPropagation() {
        $this -> propagationStopped = TRUE;
    }

    /**
     * Returns the Event name
     *
     * @return string
     *  Event name
     */
    public function getName() {
        return $this -> name;
    }
}