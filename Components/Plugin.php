<?php

namespace Next\Components;

use Next\Application\Application;                   # Application Interface
use Next\View\View;                                 # View Engine Interface

use Next\Components\Object;                         # Object Class
use Next\Components\Invoker;                        # Invoker Class

use Next\Components\Events\Event;                   # Event Class
use Next\Components\Events\Handler as EventHandler; # Event Handler Class
use Next\Components\Events\Listener;                # Event Listener Class

use Next\DB\Table\Manager;                          # Table Manager Class

class Plugin extends Object {

    /**
     * Event Handler Object
     *
     * @var Next\Components\Events\Handler
     */
    protected $eventHandler;

    /**
     * Application Object
     *
     * @var Next\Application\Application $application
     */
    protected $application;

    /**
     * Table Manager
     *
     * @var Next\DB\Table\Manager $manager
     */
    protected $manager;

    /**
     * Request Object
     *
     * @var Next\HTTP\Request $request
     */
    protected $request;

    /**
     * Response Object
     *
     * @var Next\HTTP\Response $response
     */
    protected $response;

    /**
     * View Engine
     *
     * @var Next\View\View $view
     */
    protected $view;

    /**
     * Flag to condition whether the Plugin is running in Development Mode
     *
     * @var boolean $developmentMode
     */
    protected $developmentMode = FALSE;

    /**
     * Plugin Constructor
     * Defines Application Object and an optional Table Manager,
     * configures "shortcuts" to application resources, sets the Development Mode
     * flag and creates Event Handlers to be used within real Plugins
     *
     * @param Next\Application\Application  $application
     *  Application Object
     *
     * @param Next\DB\Table\Manager|optional $manager
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

            $this -> extend( new Invoker( $this, $this -> manager ), array( 'addRepository', 'getRepository', 'getRepositories' ) );
        }

        $this -> addDefaultEventListeners();
    }

    /**
     * Routines to be performed after any possible post-processing, but
     * before the Plugin can be activated. E.g: Before it's added in a database
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onBeforeActivate( array $args = array() ) {}

    /**
     * Routines to be performed after the Plugin can be activated.
     * E.g: After it's added in a database
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
     * Routines to be performed before the Plugin can be modified.
     * E.g: Before its data is updated in a database
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onBeforeModify( array $args = array() ) {}

    /**
     * Routines to be performed after the Plugin can be modified.
     * E.g: After its data is updated in a database
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onAfterModify( array $args = array() ) {}

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
     * can't foreknow its own path so this method may render something like a
     * GUI where the user input the mentioned path manually
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function onBeforeTest( array $args = array() ) {}

    /**
     * The test routine, that ensures whatever the main routine did
     * wasn't accidentally (and manually undone later)
     *
     * @param array|optional $args
     *  Additional arguments for execution
     */
    public function test( array $args = array() ) {}

    // Accessors

    /**
     * Get Event Handler Event Object
     *
     * @return Next\Components\Events\Event
     *  The Event Object
     */
    public function getEvent() {
        return $this -> eventHandler;
    }

    /**
     * Get Event Handler Object
     *
     * @return Next\Components\Events\Handler
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
     *                 non crucial to Plugin execution failed
     * 'info'       => For informational purposes, like a post-processing tip
     * 'error'      => For when something not happened as expected.
     *                 Usually this is used when the Plugin decides to not use
     *                 the APIException concept -OR- Plugin Manager don't handle
     *                 these Exceptions or handle them as not stoppable errors
     *
     * All default Event Listeners are provided as implementation of
     * Next\Components\Event\Listener and requires, additionally to the
     * Event Object passed automatically by Event Handler,
     * an Object instance of Next\View\View and a string in order to create the
     * Template Variable (success, warning, error and info, respectively)
     *
     * Also, by default, both 'info' and 'error' Event Listeners have their propagation
     * stopped, so once they are handled by Event Handler, no other Listeners will
     * be further handled
     *
     * @return void
     *
     * @see Next\Components\Events\Handler::addListener()
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