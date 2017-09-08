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

use Next\Components\Debug\Exception;             # Exception Class
use Next\Controller\Router\RouterException;      # Router Exception Class
use Next\Controller\ControllerException;         # Controller Chain Exception Class
use Next\Cache\CacheException;                   # Cache Exception Class

use Next\Controller\Router\Router;               # Router Interface

use Next\Components\Object;                      # Object Class

use Next\HTTP\Request;                           # Request Class
use Next\HTTP\Response;                          # Response Class

use Next\Controller\Chain as ControllerChain;    # Controllers Chain Class
use Next\View\View;                              # View Interface
use Next\Cache\Schema\Chain as CachingChain;     # Controllers Chain Class

use Next\Session\Manager as Session;             # Session Manager

/**
 * Defines the base structure for an Application created with Next Framework
 *
 * @package    Next\Application
 */
abstract class AbstractApplication extends Object implements Application {

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
     * @var \Next\Cache\Schema\Chain $cache
     */
    protected $cache;

    /**
     * Session Manager
     *
     * @var \Next\Session\Manager $session
     */
    protected $session;

    /**
     * Constructor Overwriting.
     * Sets up a type-hinted Application Object for all Caching Schema
     *
     * @param mixed|\Next\Components\Object|\Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for Caching Schema
     */
    public function __construct( $options = NULL ) {

        // Setting Up Application's Resources...

            // Request and Response Objects

        $this -> request  = new Request;
        $this -> response = new Response;

            // Router

        $this -> setupRouter();

            // Database Adapters

        $this -> setupDatabase();

            /**
             * Session
             *
             * @internal
             *
             * Session Manager is initialized before View Engine because
             * View Engines *should* provide a way of Templates to
             * access Session Environment Data, but if a Session is not
             * yet available that wouldn't be possible
             *
             * If an Exception is caught here we'll rethrow it as a
             * \Next\Application\ApplicationException. Because this is
             * the lowest possible Exception in the Request/Response Flow,
             * while on Production Mode, - i.e DEVELOMENT_MODE constant
             * is not defined or set to zero - only a simple message
             * would appear
             */
        try {

            $this -> session = $this -> initSession();

        } catch( Exception $e ) {

            throw new ApplicationException(
                $e -> getMessage(), NULL, NULL, $e -> getResponseCode()
            );
        }

            // View Engine

        $this -> setupView();

            // Controllers Classes

        $this -> controllers = new ControllerChain;

        $this -> setupControllers();

            // Caching

        $this -> cache = new CachingChain;

        $this -> initCache();

            // Additional Initialization

        $this -> init();

        // Checking Application's Integrity

        $this -> checkIntegrity();

        parent::__construct( $options );
    }

    /**
     * Router Setup.
     *
     * It's **not** abstract because not all Applications require
     * a Routing System
     */
    protected function setupRouter() {}

    /**
     * Database(s) Setup.
     *
     * It's **not** abstract because not all Applications require
     * a Database.
     *
     * Our built-in HandlersApplication is an example of that.
     *
     * @see Next\Components\Debug\Handlers\HandlersApplication
     */
    protected function setupDatabase() {}

    /**
     * View Engine Setup.
     *
     * It's **not** abstract because not all the Applications require
     * a View Engine
     */
    protected function setupView() {}

    /**
     * Caching Initialization.
     *
     * It's **not** abstract because not all the Applications require
     * a Caching System
     */
    protected function initCache() {}

    /**
     * Session Initialization.
     *
     * It's **not** abstract because not all the Applications require
     * a Session interaction
     */
    protected function initSession() {}

    // Application Interface Methods Implementation

    /**
     * Get Application Directory.
     *
     * Application directory comes from Application Class NameSpace
     *
     * @return string
     *  Application Class Namespace
     */
    public function getApplicationDirectory() {
        return $this -> getClass() -> getNamespaceName();
    }

    /**
     * Set Request Object.
     *
     * @param \Next\HTTP\Request $request
     *  Request Object
     *
     * @return \Next\Application\Application
     *  Application Instance (Fluent Interface)
     */
    public function setRequest( Request $request ) {

        $this -> request =& $request;

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

        $this -> response =& $response;

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
     * @return \Next\Cache\Schema\Chain
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
     *
     * @internal
     *
     * Abstract because every Application must define its own Controllers
     */
    abstract protected function setupControllers();

    // Auxiliary Methods

    /**
     * Checks Application Integrity
     *
     * @throws \Next\Application\ApplicationException
     *  Application has an invalid Router assigned
     *
     * @throws \Next\Application\ApplicationException
     *  Assigned Application has an invalid View Engine assigned
     */
    private function checkIntegrity() {

        // Checking if assigned Router is valid

        if( ! is_null( $this -> router ) && ! $this -> router instanceof Router ) {
            throw ApplicationException::invalidRouter();
        }

        // Checking if assigned View Engine is Valid

        if( ! is_null( $this -> view ) && ! $this -> view instanceof View ) {
            throw ApplicationException::invalidViewEngine();
        }

        // Checking if assigned Session Manager is Valid

        if( ! is_null( $this -> session ) && ! $this -> session instanceof Session ) {
            throw ApplicationException::invalidSessionManager();
        }
    }
}