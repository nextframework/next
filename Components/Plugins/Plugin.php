<?php

/**
 * Plugin Component Class | Components\Plugins\Plugin.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Plugins;

use Next\Components\Object;     # Object Class
use Next\Components\Invoker;    # Invoker Class

/**
 * Base structure of a Plugin Object, with intermediary routines before and
 * after every action, from its installation in a Application Object to their
 * physical removal from the disk
 *
 * @package    Next\Components
 *
 * @uses       Next\Components\Object
 *             Next\Components\Invoker
 *             Next\Components\Plugins\Event
 *             Next\Components\Plugins\Handler
 *             Next\Components\Plugins\Listener
 */
abstract class Plugin extends Object {

    /**
     * Plugins API File
     *
     * Although more complex Plugins may require a more defined workflow, they
     * should be able to be managed somehow, externally.
     *
     * And in order to simplify this probable "Plugins Manager", the Plugin class
     * provides a fixed name for the classes that come to extend this one.
     *
     * This is not required by any means, though. And the developer must consult
     * any documentations available for this possible "Plugins Manager",
     * accordingly to the target application
     *
     * @var string
     */
    const API  = 'API.php';

    /**
     * Plugins Exceptions FIle
     *
     * The same consideration as above, but this refers to the file where
     * the Exception messages raised by the Plugin *may* reside
     *
     * @var string
     */
    const EXCEPTIONS = 'APIException.php';

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'application'  => [ 'type' => 'Next\Application\Application', 'required' => TRUE ],
        'manager'      => [ 'type' => 'Next\DB\Entity\Manager',       'required' => FALSE ],

        /**
         * Options Parameter Option to condition whether the Plugin is
         * running in Development Mode
         *
         * While running in Development Mode the Plugin may require
         * manual data to be informed while in Production it doesn't
         * need because they may come from external sources
         *
         * Defaults to FALSE
         */
        'development'  => FALSE,

        /**
         * @internal
         *
         * Optional Parameter Option to condition whether the Plugin
         * is configurable or not.
         *
         * Defaults to FALSE
         */
        'configurable' => FALSE,

        /**
         * If the Plugin has routes to be dispatched, in order to
         * minimize routing conflicts, all of them *should* be prefixed
         * with a custom string definable through this Parameter Option
         *
         * It should be something as unique as possible,
         * like a vendor name
         *
         * Defaults to NULL
         */
        'routesBaseURI' => NULL,

        /**
         * If the Plugin can be configured -AND- has a dedicated routed
         * page to do so, the "Plugins Manager", if applied, *could*
         * use this Parameter Option in order to build a richer UI
         *
         * Note, however, that this will not create the route
         * automatically and it should be prefixed with
         * \Next\Components\Plugins\Plugin::$routesBaseURI for the same reason
         * as any other route
         *
         * Defaults to 'settings' (no quotes)
         */
        'configurationRoute' => 'settings',

        /**
         * Optional Plugin internal name, to differentiate the Event in case of
         * multiple Plugins being used together.
         * Defaults to 'Plugin'
         */
        'name' => [ 'required' => FALSE, 'default' => 'Plugin' ]
    ];

    /**
     * Event Handler Object
     *
     * @var \Next\Components\Plugins\Handler
     */
    protected $eventHandler;

    /**
     * Additional Initialization.
     *
     * - Creates the Event Handler and its Event Object
     * - Extends Plugin Context to *some* of the methods of
     *   \Next\Application\Application (required) and
     *   \Next\DB\Entity\Entity (optional) defined as Parameter Options
     *  - Creates default Event Listeners
     */
    protected function init() : void {

        $this -> eventHandler = new Handler(
            [ 'event' => new Event( [ 'name' => $this -> options -> name ] ) ]
        );

        /**
         * @internal
         *
         * Extending Plugin Context to *some* of the
         * \Next\Application\Application accessory methods
         */
        $this -> extend(

            new Invoker( $this, $this -> options -> application ),

            [ 'getRequest', 'getResponse', 'getView' ]
        );

        $this -> addDefaultEventListeners();
    }

    /**
     * Routines to be performed after any possible post-processing, but
     * before the Plugin can be activated. E.g: Before it's added to a database
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onBeforeActivate( array $args = [] ) {}

    /**
     * Routines to be performed after the Plugin can be activated.
     * E.g: After it's added to a database
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onAfterActivate( array $args = [] ) {}

    /**
     * Routines to be performed before the Plugin can be unregistered.
     * E.g: Before it's removed from a database
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onBeforeDeactivate( array $args = [] ) {}

    /**
     * Routines to be performed after the Plugin can be unregistered.
     * E.g: After it's removed from a database
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onAfterDeactivate( array $args = [] ) {}

    /**
     * Routines to be performed before the Plugin can be deleted
     * E.g: Before it's removed from the physical disk
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onBeforeRemove( array $args = [] ) {}

    /**
     * Routines to be performed after the Plugin can be deleted.
     * E.g: After it's removed from the physical disk
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onAfterRemove( array $args = [] ) {}

    /**
     * Routines to be performed before the Plugin can be edited.
     * E.g: Before its data is updated in a database
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onBeforeEdit( array $args = [] ) {}

    /**
     * Routines to be performed after the Plugin can be edited.
     * E.g: After its data is updated in a database
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onAfterEdit( array $args = [] ) {}

    /**
     * Routines to be performed before the Plugin is executed.
     * E.g: A Plugin that provides a scheduled backup functionality could delete
     * backup files that are too old
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onBeforeRun( array $args = [] ) {}

    /**
     * The main routine, with all the logics of the Plugin,
     * preferably without any direct output
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function run( array $args = [] ) {}

    /**
     * Routines to be performed after the Plugin is executed.
     * E.g: A Plugin that provides a scheduled backup functionality could send
     * the currently generated file as email attachment to the System Administrator
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onAfterRun( array $args = [] ) {}

    /**
     * Routines to be performed before the Plugin is tested.
     * E.g: A Plugin that manipulates an external source with checkable
     * informations, like an SQLite database file with settings of any sort,
     * can't foreknow its own path, so this method may render something like a
     * GUI where the user input the mentioned path manually
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onBeforeTest( array $args = [] ) {}

    /**
     * The test routine, that ensures whatever the main routine did
     * wasn't accidentally (and manually) undone later
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function test( array $args = [] ) {}

    // Accessory Methods

    /**
     * Get Event Handler Object
     *
     * @return \Next\Components\Plugins\Handler
     *  The Event Handler
     */
    public function getEventHandler() : Handler {
        return $this -> eventHandler;
    }

    // Auxiliary Methods

    /**
     * Adds default Event Listeners to be used within Plugins
     *
     * There are four Event Listeners by default:
     *
     * 'success'    => For when everything goes as expected
     * 'warning'    => For when the main purpose goes as expected,
     *                 but something non-critical to the execution
     *                 of the Plugin failed
     * 'info'       => For informational purposes, like a
     *                 post-processing tip
     * 'error'      => For when something not happened as expected.
     *                 Usually this is used when the Plugin decides
     *                 to not use the APIException concept -OR-
     *                 an implemented Plugin Manager doesn't handle
     *                 these Exceptions or handle them as not stoppable
     *                 errors
     *
     * All default Event Listeners are provided as implementation of
     * \Next\Components\Plugins\Listener and requires, additionally to the
     * Event Object passed automatically by Event Handler, a string
     * with the message in order to be registered as Handled Result
     *
     * Also, the Template Variables assignments it's up to the Developer
     * using the Plugin feature. This way there's no variable conflicts
     *
     * And note that, by default, 'error' Event Listener has its
     * propagation stopped, so once it's handled by Event Handler,
     * no other Listeners will be further handled, after all it
     * represents a critical incident
     *
     * @see \Next\Components\Plugins\Handler::addListener()
     * @see \Next\Components\Plugins\Handler::getHandledResults()
     */
    private function addDefaultEventListeners() : void {

        $this -> eventHandler -> addListener(

            'success', new Listener( function( Event $event, $message ) : string {
                return $message;
            })
        );

        $this -> eventHandler -> addListener(

            'warning', new Listener( function( Event $event, $message ) : string {
                return $message;
            })
        );

        $this -> eventHandler -> addListener(

            'info', new Listener( function( Event $event, $message ) : string {
                return $message;
            })
        );

        $this -> eventHandler -> addListener(

            'error', new Listener( function( Event $event, $message ) : string {

                $event -> stopPropagation();

                return $message;
            })
        );
    }
}