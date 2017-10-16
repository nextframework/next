<?php

/**
 * Application Abstract Class | Application/AbstractApplication.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Application;

/**
 * Exception Classes
 */
use Next\Exception\Exception;
use Next\Exception\Exceptions\FatalException;
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Interfaces\Verifiable;       # Verifiable Interface
use Next\Controller\Router\Router;               # Router Interface
use Next\Components\Object;                      # Object Class
use Next\Cache\Schemas\Chain as CachingChain;    # Caching Schemas Chain Class
use Next\Controller\Chain as ControllerChain;    # Controllers Chain Class
use Next\View\View;                              # View Engine Interface
use Next\HTTP\Request;                           # Request Class
use Next\HTTP\Response;                          # Response Class
use Next\Session\Manager as Session;             # Session Manager

/**
 * Defines the base structure for an Application created with Next Framework
 *
 * @package    Next\Application
 */
abstract class AbstractApplication extends Object implements Verifiable, Application {

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
     * Router
     *
     * @var \Next\Controller\Router\Router $router
     */
    protected $router;

    /**
     * Controllers Chain
     *
     * @var \Next\Controller\Chain $controllers
     */
    protected $controllers;

    /**
     * View Engine
     *
     * @var \Next\View\View $view
     */
    protected $view;

    /**
     * Caching Services Chain
     *
     * @var \Next\Cache\Schemas\Chain $cache
     */
    protected $cache;

    /**
     * Session Manager
     *
     * @var \Next\Session\Manager $session
     */
    protected $session;

    /**
     * Application Constructor
     *
     * Configures:
     *
     * - Request and Response Objects
     * - Application Router
     * - Database Connection
     * - Session
     * - View Engine
     * - Application Controllers
     * - Caching Schemas
     */
    public function __construct() {

        try {

            // Request and Response Objects

            $this -> request  = new Request;
            $this -> response = new Response;

            // Router

            $this -> router = $this -> setupRouter();

            // Database Connections

            $this -> setupDatabase();

            /**
             * Session
             *
             * @internal
             *
             * Session Manager is initialized before the View Engine
             * because View Engines *should* provide a way for
             * Templates to access Session Environment Data, but if a
             * Session is not yet available that wouldn't be possible
             */
            $this -> session = $this -> initSession();

            // View Engine

            $this -> view = $this -> setupView();

            /**
             * Controllers' Chain and classes
             *
             * @internal
             *
             * They're only needed when the DEVELOPMENT_MODE constant is
             * defined and set to '2', case in which the Routes Generator
             * would take place
             */
            if( defined( 'DEVELOPMENT_MODE' ) && DEVELOPMENT_MODE == 2 ) {

                $this -> controllers = new ControllerChain;

                $this -> setupControllers();
            }

            // Caching

            $this -> cache = new CachingChain;

            $this -> initCache();

        } catch( Exception $e ) {

            /**
             * @internal
             *
             * A `Next\Application\Application` is one of the
             * foundations of Next Framework's MVC so if any problem
             * at all happens here we just re-throw as a
             * `\Next\Exception\Exceptions\FatalException`, the lowest
             * possible kind of Exception handled directly by the
             * Controllers' Dispatcher
             */
            throw new FatalException(
                $e -> getMessage(), $e -> getCode(), $e -> getResponseCode()
            );
        }

        parent::__construct();
    }

    /**
     * Router Setup
     *
     * @internal
     *
     * It's **not** abstract because not all Applications require
     * a Routing System
     */
    protected function setupRouter() {}

    /**
     * Database(s) Setup
     *
     * @internal
     *
     * It's **not** abstract because not all Applications require
     * a Database
     *
     * Our built-in HandlersApplication is an example of that
     *
     * @see Next\Exception\Handlers\HandlersApplication
     */
    protected function setupDatabase() {}

    /**
     * View Engine Setup
     *
     * @internal
     *
     * It's **not** abstract because not all the Applications require
     * a View Engine
     */
    protected function setupView() {}

    /**
     * Caching Initialization
     *
     * @internal
     *
     * It's **not** abstract because not all the Applications require
     * a Caching System
     */
    protected function initCache() {}

    /**
     * Session Initialization
     *
     * @internal
     *
     * It's **not** abstract because not all the Applications require
     * a Session interaction
     */
    protected function initSession() {}

    // Application Interface Methods Implementation

    /**
     * Get Application Directory
     *
     * Application directory comes from Application Class NameSpace
     *
     * @return string
     *  Application Class' Fully Qualified Namespace
     */
    public function getApplicationDirectory() {
        return $this -> getClass() -> getNamespaceName();
    }

    /**
     * Set Request Object
     *
     * @param \Next\HTTP\Request $request
     *  Request Object
     *
     * @return \Next\Application\Application
     *  Application Instance (Fluent Interface)
     */
    public function setRequest( Request $request ) {

        $this -> request = $request;

        return $this;
    }

    /**
     * Get Request Object
     *
     * @return \Next\HTTP\Request
     *  Request Object
     */
    public function getRequest() {
        return $this -> request;
    }

    /**
     * Set Response Object
     *
     * @param \Next\HTTP\Response $response
     *  Response Object
     *
     * @return \Next\Application\Application
     *  Application Instance (Fluent Interface)
     */
    public function setResponse( Response $response ) {

        $this -> response = $response;

        return $this;
    }

    /**
     * Get Response Object
     *
     * @return \Next\HTTP\Response
     *  Response Object
     */
    public function getResponse() {
        return $this -> response;
    }

    /**
     * Get Router
     *
     * @return \Next\Controller\Router\Router
     *  Router Object
     */
    public function getRouter() {
        return $this -> router;
    }

    /**
     * Get View Engine
     *
     * @return \Next\View\View
     *  View Engine Object
     */
    public function getView() {
        return $this -> view;
    }

    /**
     * Get all Controller Objects associated to the Application
     *
     * @return \Next\Controller\Chain
     *  Controllers Collection Object
     */
    public function getControllers() {
        return $this -> controllers;
    }

    /**
     * Get Caching Schema Chain
     *
     * @return \Next\Cache\Schemas\Chain
     *  Caching Schema Collection Chain Object
     */
    public function getCache() {
        return $this -> cache;
    }

    /**
     * Get Session Manager
     *
     * @return \Next\Session\Manager
     *  Session Manager Object
     */
    public function getSession() {
        return $this -> session;
    }

    // Abstract Methods Definition

    /**
     * Controllers Setup
     */
    abstract protected function setupControllers();

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if an HTTP Router has been assigned but it's not valid
     *  because it doesn't implement `\Next\Controller\Router\Router`
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if a View Engine has been assigned but it's not valid
     *  because it doesn't implement `\Next\View\View` Interface
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if a Session Manager has been assigned but it's not
     *  valid because, currently, only Objects instance of
     *  `\Next\Session\Manager` are accepted
     */
    public function verify() {

        if( $this -> router !== NULL && ! $this -> router instanceof Router ) {

            throw new InvalidArgumentException(
                'Routers must implement <em>Next\Controller\Router\Router</em> Interface'
            );
        }

        if( $this -> view !== NULL && ! $this -> view instanceof View ) {

            throw new InvalidArgumentException(
                'View Engines must implement View <em>Next\View\View</em> Interface'
            );
        }

        if( $this -> session !== NULL && ! $this -> session instanceof Session ) {

            throw new InvalidArgumentException(
                'Session Manager must be an instance of <em>Next\Session\Manager</em>'
            );
        }
    }
}