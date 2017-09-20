<?php

/**
 * Events Component Class | Components\Events\Event.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
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
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'name' => [ 'default' => 'Next' ]
    ];

    /**
     * Flag to condition whether no further Event Listeners should be triggered
     *
     * @var bool $propagationStopped
     */
    protected $propagationStopped = FALSE;

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
     * Starts (or restarts) the propagation of the Event to
     * further Event Listeners.
     *
     * If multiple Event Listeners are connected to the same Event,
     * and one of Event Listeners stopped the propagation, this allow
     * sequential Listeners to be fired again
     *
     * This is useful if an Event Handler responsible for a
     * critical error is fired and stopped the propagation, and a
     * complementary, but not crucial, task also fails and needs to
     * inform the endpoint too
     */
    public function startPropagation() {
        $this -> propagationStopped = FALSE;
    }

    /**
     * Stops the propagation of the Event to further Event Listeners.
     *
     * If multiple Event listeners are connected to the same event,
     * further Event Listeners will not be triggered once any trigger
     * calls this method
     */
    public function stopPropagation() {
        $this -> propagationStopped = TRUE;
    }

    // Accessors

    /**
     * Get the Event name
     *
     * @return string
     *  The Event name
     */
    public function getName() {
        return $this -> options -> name;
    }
}