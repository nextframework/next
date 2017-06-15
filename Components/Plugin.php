<?php

/**
 * Plugin Component Class | Components\Plugin.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Components;

use Next\Application\Application;                      # Application Interface
use Next\View\View;                                    # View Engine Interface

use Next\Components\Object;                            # Object Class
use Next\Components\Invoker;                           # Invoker Class

use Next\Components\Events\Event;                      # Event Class
use Next\Components\Events\Handler as EventHandler;    # Event Handler Class
use Next\Components\Events\Listener;                   # Event Listener Class

use Next\DB\Table\Manager;                             # Table Manager Class

/**
 * Defines the base of a Plugin Object, with intermediary routines
 * before and after every action, from its installation in a
 * \Next\Application\Application to their physical removal from the disk
 *
 * @package    Next\Components
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
     * Event Handler Object
     *
     * @var \Next\Components\Events\Handler
     */
    protected $eventHandler;

    /**
     * Application Object
     *
     * @var \Next\Application\Application $application
     */
    protected $application;

    /**
     * Table Manager
     *
     * @var \Next\DB\Table\Manager $manager
     */
    protected $manager;

    /**
     * Request Object
     *
     * @var \Next\HTTP\Request $request
     */
    protected $request;

    /**
     * Response Object
     *
     * @var \Next\HTTP\Response $response
     */
    protected $response;

    /**
     * View Engine
     *
     * @var \Next\View\View $view
     */
    protected $view;

    /**
     * Flag to condition whether the Plugin is running in Development Mode
     *
     * While running in Development Mode the Plugin may require manual data to
     * be informed while in Production it doesn't need because they may come
     * from external sources
     *
     * @var boolean $developmentMode
     */
    protected $developmentMode = FALSE;

    /**
     * If the Plugin has routes to be dispatched, in order to minimize routing
     * conflicts, all of them *should* be prefixed with a custom string definable
     * through this property
     *
     * It should be something as unique as possible, like a vendor name
     *
     * @var string|optional $routesBaseURI
     */
    protected $routesBaseURI;

    /**
     * Flag to condition whether the Plugin is configurable or not
     *
     * @var boolean $configurable
     */
    protected $configurable = FALSE;

    /**
     * If the Plugin can be configured -AND- has a dedicated routed page
     * to do so, the "Plugin Manager" *may* use this string defined in this
     * property in order to build a richer GUI
     *
     * Note, however, that this will not create the route automatically and
     * it should be prefixed with \Next\Components\Plugin::$routesBaseURI
     * for the same reason as any other route
     *
     * @var string $configurationRoute
     */
    protected $configurationRoute = 'settings';

    /**
     * Plugin Constructor
     * Defines Application Object and an optional Table Manager,
     * configures "shortcuts" to application resources, sets the Development Mode
     * flag and creates Event Handlers to be used within real Plugins
     *
     * @param \Next\Application\Application $application
     *  Application Object
     *
     * @param \Next\DB\Table\Manager|optional $manager
     *  Table Manager to bridge Entity Repositories
     */
    public function __construct( Application $application, Manager $manager = NULL ) {

        $this -> eventHandler = new EventHandler( new Event( 'Plugin' ) );

        $this -> application = $application;

        // Shortcuts

        $this -> request  = $application -> getRequest();
        $this -> response = $application -> getResponse();
        $this -> view     = $application -> getView();

        /**
         * @internal
         * The Development Mode reads an Environment Variable defined through putenv()
         */
        $this -> developmentMode = ( $this -> request -> getEnv( 'DEVELOPMENT' ) === '1' );

        parent::__construct();

        if( ! is_null( $manager ) ) {

            $this -> manager = $manager;

            $this -> extend(
                new Invoker( $this, $this -> manager ),
                array( 'addRepository', 'getRepository', 'getRepositories' )
            );
        }

        $this -> addDefaultEventListeners();
    }

    /**
     * Routines to be performed after any possible post-processing, but
     * before the Plugin can be activated. E.g: Before it's added to a database
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onBeforeActivate( array $args = array() ) {}

    /**
     * Routines to be performed after the Plugin can be activated.
     * E.g: After it's added to a database
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onAfterActivate( array $args = array() ) {}

    /**
     * Routines to be performed before the Plugin can be unregistered.
     * E.g: Before it's removed from a database
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onBeforeDeactivate( array $args = array() ) {}

    /**
     * Routines to be performed after the Plugin can be unregistered.
     * E.g: After it's removed from a database
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onAfterDeactivate( array $args = array() ) {}

    /**
     * Routines to be performed before the Plugin can be deleted
     * E.g: Before it's removed from the physical disk
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onBeforeRemove( array $args = array() ) {}

    /**
     * Routines to be performed after the Plugin can be deleted.
     * E.g: After it's removed from the physical disk
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onAfterRemove( array $args = array() ) {}

    /**
     * Routines to be performed before the Plugin can be edited.
     * E.g: Before its data is updated in a database
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onBeforeEdit( array $args = array() ) {}

    /**
     * Routines to be performed after the Plugin can be edited.
     * E.g: After its data is updated in a database
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onAfterEdit( array $args = array() ) {}

    /**
     * Routines to be performed before the Plugin is executed.
     * E.g: A Plugin that provides a scheduled backup functionality could delete
     * backup files that are too old
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onBeforeRun( array $args = array() ) {}

    /**
     * The main routine, with all the logics of the Plugin,
     * preferably without any direct output
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function run( array $args = array() ) {}

    /**
     * Routines to be performed after the Plugin is executed.
     * E.g: A Plugin that provides a scheduled backup functionality could send
     * the currently generated file as email attachment to the System Administrator
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onAfterRun( array $args = array() ) {}

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
    public function onBeforeTest( array $args = array() ) {}

    /**
     * The test routine, that ensures whatever the main routine did
     * wasn't accidentally (and manually) undone later
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function test( array $args = array() ) {}

    // Accessors

    /**
     * Returns whether or not the Plugin *may* be configurable
     *
     * Configurable Plugins doesn't mean necessarily Plugins' Objects Configuration.
     * Although this class' constructor has been overwritten,
     * \Next\Components\Object::__constructor() has been called under parent context
     * and thus all resources, including those provided by \Next\Components\Parameter
     * are still available
     *
     * @return boolean
     *   Whether the Plugin is configurable
     */
    public function isConfigurable() {
        return (boolean) $this -> configurable;
    }

    /**
     * Returns the base URI defined by Plugin in case it has routes to be dispatched
     *
     * @return boolean
     *   Routes base URI
     */
    public function getRoutesBaseURI() {
        return $this -> routesBaseURI;
    }

    /**
     * Returns the special configuration route defined
     *
     * @return string
     *   Configuration route
     */
    public function getConfigurationRoute() {
        return $this -> configurationRoute;
    }

    /**
     * Get Event Handler Event Object
     *
     * @return \Next\Components\Events\Event
     *  The Event Object
     */
    public function getEvent() {
        return $this -> eventHandler;
    }

    /**
     * Get Event Handler Object
     *
     * @return \Next\Components\Events\Handler
     *  The Event Handler
     */
    public function getEventHandler() {
        return $this -> eventHandler;
    }

    // Auxiliary Methods

    /**
     * Add default Event Listeners to be used within Plugins
     *
     * There are four Event Listeners by default:
     *
     * 'success'    => For when everything went as expected
     * 'warning'    => For when the main purpose went as expected, but something
     *                 non-critical to the execution of the Plugin failed
     * 'info'       => For informational purposes, like a post-processing tip
     * 'error'      => For when something not happened as expected.
     *                 Usually this is used when the Plugin decides to not use
     *                 the APIException concept -OR- Plugin Manager don't handle
     *                 these Exceptions or handle them as not stoppable errors
     *
     * All default Event Listeners are provided as implementation of
     * \Next\Components\Event\Listener and requires, additionally to the
     * Event Object passed automatically by Event Handler,
     * an Object instance of \Next\View\View and a string with the message
     * in order to create the Template Variable (success, warning, error and info, respectively)
     *
     * Also, by default, both 'info' and 'error' Event Listeners have their propagation
     * stopped, so once they are handled by Event Handler, no other Listeners will
     * be further handled
     *
     * @see \Next\Components\Events\Handler::addListener()
     */
    private function addDefaultEventListeners() {

        $this -> eventHandler -> addListener(

            'success', new Listener( function( Event $event, View $view, $message ) {

                $view -> success = $message;
            })
        );

        $this -> eventHandler -> addListener(

            'warning', new Listener( function( Event $event, View $view, $message ) {

                $view -> warning = $message;
            })
        );

        $this -> eventHandler -> addListener(

            'error', new Listener( function( Event $event, View $view, $message ) {

                $event -> stopPropagation();

                $view -> error = $message;
            })
        );

        $this -> eventHandler -> addListener(

            'info', new Listener( function( Event $event, View $view, $message ) {

                $event -> stopPropagation();

                $view -> info = $message;
            })
        );
    }
}