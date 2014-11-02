<?php

namespace Next\Application;

use Next\Controller\ControllerException;         # Controller Chain Exception Class
use Next\Controller\Router\RouterException;      # Router Exception Class
use Next\View\View;                              # View Interface
use Next\Controller\Router\Router;               # Router Interface
use Next\Components\Object;                      # Object Class
use Next\Controller\Chain as ControllerChain;    # Controllers Chain Class
use Next\Controller\Router\Standard;             # Standard Router Class
use Next\HTTP\Request, Next\HTTP\Response;       # Next Request & Response Classes

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
     * Router
     *
     * @var Next\Controller\Router\Router $router
     */
    protected $router;

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
     * Application Constructor
     *
     * @param Next\Controller\Router\Router|optional $router
     *
     *  <p>URL Router Class designed to this specific Application.</p>
     *
     *  <p>
     *      If NULL, the Standard Router (based in SQLITE Databases)
     *      will be used instead.
     *  </p>
     *
     * @param Next\HTTP\Request|optional $request
     *
     *  <p>Customized Request Object</p>
     *
     *  <p>
     *      If NULL, an unmodified Request object will be used instead
     *  </p>
     *
     * @param Next\HTTP\Response
     *
     *  <p>Customized Response Object</p>
     *
     *  <p>
     *      If NULL, an unmodified Response object will be used instead
     *  </p>
     *
     * @throws Next\Application\ApplicationException
     *  Invalid Controller added to Controllers Chain
     */
    public function __construct( Router $router = NULL, Request $request = NULL, Response $response = NULL ) {

        // Setting Up Application's Resources...

            // Controllers Classes

        $this -> controllers = new ControllerChain;

        try {

            $this -> setupControllers();

        } catch( ControllerException $e ) {

            throw new ApplicationException(

                $e -> getMessage()
            );
        }

            // Request and Response Objects

        $this -> request  = ( ! is_null( $request ) ? $request : new Request );

        $this -> response = ( ! is_null( $response ) ? $response : new Response );

            // Router

        try {

            $this -> router = ( ! is_null( $router ) ? $router : new Standard );

        } catch( RouterException $e ) {

            throw new ApplicationException( $e -> getMessage() );
        }

            // View Engine

        $this -> setupView();

            // Database Adapters

        $this -> setupDatabase();

            // Localization / Internationalization

        $this -> setupLocale();

        // Additional Initialization

        $this -> init();

        // Checking Application's Integrity

        $this -> checkIntegrity();
    }

    /**
     * Additional Initialization. Must be overwritten
     */
    protected function init() {}

    /**
     * View Engine Setup
     *
     * It's NOT abstract because not all the Applications requires a View Engine
     */
    protected function setupView() {}

    /**
     * Database(s) Setup
     *
     * It's NOT abstract because not all the Applications requires a Database
     * Our built-in HandlersApplication is an example of that.
     */
    protected function setupDatabase() {}

    /**
     * Localization/Internationalization Setup
     *
     * It's NOT abstract because not all the Applications requires Localization
     */
    protected function setupLocale() {}

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
     * Get View Engine
     *
     * @return Next\View\View
     *  View Engine Object
     */
    public function getView() {
        return $this -> view;
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
     *  Application has no View Engine assigned
     *
     * @throws Next\Application\ApplicationException
     *  Assigned View Engine is invalid over interface implementing check
     */
    private function checkIntegrity() {

        // Checking if we have some View Engine Registered

        if( is_null( $this -> view ) ) {

            throw ApplicationException::noViewEngine( $this );
        }

        // Checking if assigned View Engine is Valid

        if( ! $this -> view instanceof View ) {

            throw ApplicationException::invalidViewEngine();
        }
    }
}