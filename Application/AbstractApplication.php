<?php

namespace Next\Application;

use Next\Controller\Router\RouterException;      # Router Exception Class
use Next\Controller\ControllerException;         # Controller Chain Exception Class
use Next\Cache\CacheException;                   # Cache Exception Class

use Next\Controller\Router\Router;               # Router Interface

use Next\Components\Object;                      # Object Class

use Next\HTTP\Request;                           # Request Class
use Next\HTTP\Response;                          # Response Class

use Next\Controller\Chain as ControllerChain;    # Controllers Chain Class
use Next\View\View;                              # View Interface
use Next\Cache\Schema\Chain as CachingChain;    # Controllers Chain Class

/**
 * Application Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractApplication extends Object implements Application {

    /**
     * Default Options
     *
     * @var array $defaultOptions
     */
    protected $defaultOptions = array();

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
     * Router
     *
     * @var Next\Controller\Router\Router $router
     */
    protected $router;

    /**
     * Controllers Chain
     *
     * @var Next\Controller\Chain $controllers
     */
    protected $controllers;

    /**
     * View Engine
     *
     * @var Next\View\View $view
     */
    protected $view;

    /**
     * Caching Services Chain
     *
     * @var Next\Cache\Schema\Chain $cache
     */
    protected $cache;

    /**
     * Constructor Overwriting
     * Sets up a type-hinted Application Object for all Caching Schema
     *
     * @param mixed|Next\Components\Object|Next\Components\Parameter|stdClass|array|optional $options
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

            // View Engine

        $this -> setupView();

            // Controllers Classes

        $this -> controllers = new ControllerChain;

        $this -> setupControllers();

            // Caching

        $this -> cache = new CachingChain;

        $this -> setupCache();

            // Additional Initialization

        $this -> init();

        // Checking Application's Integrity

        $this -> checkIntegrity();

        parent::__construct( $options );
    }

    /**
     * Router Setup
     *
     * It's NOT abstract because not all Applications requires a Routing system
     */
    protected function setupRouter() {}

    /**
     * Database(s) Setup
     *
     * It's NOT abstract because not all the Applications requires a Database
     * Our built-in HandlersApplication is an example of that.
     */
    protected function setupDatabase() {}

    /**
     * View Engine Setup
     *
     * It's NOT abstract because not all the Applications requires a View Engine
     */
    protected function setupView() {}

    /**
     * Caching Setup
     *
     * It's NOT abstract because not all the Applications requires a Caching System
     */
    protected function setupCache() {}

    // Interface Methods (also Accessors)

    /**
     * Get Application Directory
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
     * Set Request Object
     *
     * @param Next\HTTP\Request $request
     *  Request Object
     *
     * @return Next\Application\Application
     *  Application Instance (Fluent Interface)
     */
    public function setRequest( Request $request ) {

        $this -> request =& $request;

        return $this;
    }

    /**
     * Get Request Object
     *
     * @return Next\HTTP\Request
     *  Request Object
     */
    public function getRequest() {
        return $this -> request;
    }

    /**
     * Set Response Object
     *
     * @param Next\HTTP\Response $response
     *  Response Object
     *
     * @return Next\Application\Application
     *  Application Instance (Fluent Interface)
     */
    public function setResponse( Response $response ) {

        $this -> response =& $response;

        return $this;
    }

    /**
     * Get Response Object
     *
     * @return Next\HTTP\Response
     *  Response Object
     */
    public function getResponse() {
        return $this -> response;
    }

    /**
     * Get Router
     *
     * @return Next\Controller\Router\Router
     *  Router Object
     */
    public function getRouter() {
        return $this -> router;
    }

    /**
     * Get View Engine
     *
     * @return Next\View\View
     *  View Engine Object
     */
    public function getView() {
        return $this -> view;
    }

    /**
     * Get Controllers Chain
     *
     * Get all Controller Objects associated to Application
     *
     * @return Next\Controller\Chain
     *  Controllers Collection Object
     */
    public function getControllers() {
        return $this -> controllers;
    }

    /**
     * Get Caching Schema Chain
     *
     * @return Next\Cache\Schema\Chain
     *  Caching Schema Collection Chain Object
     */
    public function getCache() {
        return $this -> cache;
    }

    // Abstract Methods Definition

    /**
     * Controllers Setup
     *
     * It's abstract because every Application must define its own Controllers
     */
    abstract protected function setupControllers();

    // Auxiliary Methods

    /**
     * Check Application Integrity
     *
     * @throws Next\Application\ApplicationException
     *  Application has an invalid Router assigned
     *
     * @throws Next\Application\ApplicationException
     *  Assigned has an invalid View Engine assigned
     */
    private function checkIntegrity() {

        // Checking if assigned Router is valid

        if( ! $this -> router instanceof Router ) {
            throw ApplicationException::invalidRouter();
        }

        // Checking if assigned View Engine is Valid

        if( ! $this -> view instanceof View ) {

            throw ApplicationException::invalidViewEngine();
        }
    }
}