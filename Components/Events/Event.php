<?php

/**
 * Events Component Class | Components\Events\Event.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Components\Events;

use Next\Components\Object;    # Object Class

/**
 * The Event Class is a manually propagated Component similar to JavaScript's Events
 *
 * @package    Next\Components\Events
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
     *
     * @param mixed|\Next\Components\Object|\Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for each Event
     */
    public function __construct( $name = NULL, $options = NULL ) {

        parent::__construct( $options );

        $this -> name = ( ! is_null( $name ) ? $name : 'Next' );
    }

    // Event bubbling-related methods

    /**
     * Returns whether or not further Event Listeners should be triggered.
     *
     * @return bool
     *   Whether propagation was already stopped for this event
     *
     * @see \Next\Components\Events\Event::stopPropagation()
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

    // Accessors

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