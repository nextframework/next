<?php

/**
 * Application Abstract Class | Application/Application.php
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
use Next\Exception\Exceptions\BadMethodCallException;
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Validation\Verifiable;                   # Verifiable Interface
use Next\View\View as Views;                      # View Engine Interface
use Next\Components\Object;                       # Object Class
use Next\Cache\Schemas\Chain as CachingChain;     # Caching Schemas Chain Class
use Next\Controller\Chain as ControllersChain;    # Controllers Chain Class
use Next\HTTP\Router\Router as Routers;           # Router Abstract Class
use Next\HTTP\Request;                            # Request Class
use Next\HTTP\Response;                           # Response Class
use Next\Session\Manager as Session;              # Session Manager

/**
 * Base structure for all Applications created with Next Framework
 *
 * @package    Next\Application
 *
 * @uses       Next\Exception\Exception
 *             Next\Exception\Exceptions\FatalException
 *             Next\Exception\Exceptions\BadMethodCallException
 *             Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Validation\Verifiable
 *             Next\HTTP\Router\Router
 *             Next\Components\Object
 *             Next\Cache\Schemas\Chain
 *             Next\Controller\Chain
 *             Next\View\View
 *             Next\HTTP\Request
 *             Next\HTTP\Response
 *             Next\Session\Manager as Session
 */
abstract class Application extends Object implements Verifiable {

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
     * @var \Next\HTTP\Router\Router $router
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
             * defined and set to '2', case in which the Routes Generation is —
             * or should be — triggered
             *
             * And generated Routes are only needed along with an URL Router,
             * so if there's not one defined, the Page Controllers won't be
             * used either
             */
            if( $this -> router !== NULL &&
                  ( defined( 'DEVELOPMENT_MODE' ) && DEVELOPMENT_MODE == 2 ) ) {

                $this -> controllers = new ControllersChain;

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

    // Abstract Methods Definition

    /**
     * Controllers Setup
     *
     * @internal
     *
     * It's **not** abstract because only Applications that require URL Routing
     * need to define Page Controllers'
     */
    protected function setupControllers() : void {}

    /**
     * View Engine Setup
     *
     * @internal
     *
     * It's **not** abstract because not all Applications require a View Engine.
     *
     * Service Applications that communicates with the Client only
     * through AJAX with JSON Responses are an example of that
     *
     * @return \Next\View\View|NULL
     * If overwritten by children classes, this method must return an Object
     * instance of Next\View\View` — and the implementation of
     *  `\Next\Validation\Verifiable::verify()` will ensure that.
     * Otherwise this returns `NULL` to satisfy the PHP 7 Return Type Declaration
     */
    protected function setupView() :? Views {
        return NULL;
    }

    /**
     * Router Setup
     *
     * @internal
     *
     * It's **not** abstract because not all Applications require a URL Routing
     *
     * Our Exception Handlers Application is an example of that
     *
     * @return \Next\HTTP\Router\Router|NULL
     * If overwritten by children classes, this method must return an Object
     * instance of Next\HTTP\Router\Router` — and the implementation of
     *  `\Next\Validation\Verifiable::verify()` will ensure that.
     * Otherwise this returns `NULL` to satisfy the PHP 7 Return Type Declaration
     */
    protected function setupRouter() :? Routers {
        return NULL;
    }

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
    protected function setupDatabase() : void {}

    /**
     * Caching Initialization
     *
     * @internal
     *
     * It's **not** abstract because not all the Applications require
     * a Caching System
     */
    protected function initCache() : void {}

    /**
     * Session Initialization
     *
     * @internal
     *
     * It's **not** abstract because not all the Applications require
     * a Session interaction
     *
     * @return \Next\Session\Manager|NULL
     * If overwritten by children classes, this method must return an Object
     * instance of Next\Session\Manager` — and the implementation of
     *  `\Next\Validation\Verifiable::verify()` will ensure that.
     * Otherwise this returns `NULL` to satisfy the PHP 7 Return Type Declaration
     */
    protected function initSession() :? Session {
        return NULL;
    }

    /**
     * Get Application Directory from Applications' Class NameSpace
     *
     * @return string
     *  Application Class' Fully Qualified Namespace
     */
    public function getApplicationDirectory() : string {
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
    public function setRequest( Request $request ) : Application {

        $this -> request = $request;

        return $this;
    }

    /**
     * Get Request Object
     *
     * @return \Next\HTTP\Request
     *  Request Object
     */
    public function getRequest() : Request {
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
    public function setResponse( Response $response ) : Application {

        $this -> response = $response;

        return $this;
    }

    /**
     * Get Response Object
     *
     * @return \Next\HTTP\Response
     *  Response Object
     */
    public function getResponse() : Response {
        return $this -> response;
    }

    /**
     * Get Router
     *
     * @return \Next\HTTP\Router\Router
     *  Router Object
     */
    public function getRouter() : Routers {
        return $this -> router;
    }

    /**
     * Get View Engine
     *
     * @return \Next\View\View
     *  View Engine Object
     */
    public function getView() : Views {
        return $this -> view;
    }

    /**
     * Get all Controller Objects associated to the Application
     *
     * @return \Next\Controller\Chain
     *  Controllers Collection Object
     *
     * @throws \Next\Exception\Exceptions\BadMethodCallException
     *  Thrown if accessing the method when the DEVELOPMENT MODE constant is
     *  not defined or is set as '2', case in which the Routes Generation
     *  is — or should be — triggered
     */
    public function getControllers() : ControllersChain {

        if( ! defined( 'DEVELOPMENT_MODE' ) || DEVELOPMENT_MODE != 2 ) {

            throw new BadMethodCallException(
                'The Page Controllers\' Chain is only available while generating URL Routes'
            );
        }

        return $this -> controllers;
    }

    /**
     * Get Caching Schema Chain
     *
     * @return \Next\Cache\Schemas\Chain
     *  Caching Schema Collection Chain Object
     */
    public function getCache() : CachingChain {
        return $this -> cache;
    }

    /**
     * Get Session Manager
     *
     * @return \Next\Session\Manager
     *  Session Manager Object
     */
    public function getSession() :? Session {
        return $this -> session;
    }

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if an HTTP Router has been assigned but it's not valid
     *  because it doesn't implement Next\HTTP\Router\Router`
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if a View Engine has been assigned but it's not valid
     *  because it doesn't implement Next\View\View` Interface
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if a Session Manager has been assigned but it's not
     *  valid because, currently, only Objects instance of
     *  `\Next\Session\Manager` are accepted
     */
    public function verify() : void {

        if( $this -> router !== NULL && ! $this -> router instanceof Routers ) {

            throw new InvalidArgumentException(
                'Routers must implement <em>Next\HTTP\Router\Router</em> Interface'
            );
        }

        if( $this -> view !== NULL && ! $this -> view instanceof Views ) {

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