<?php

/**
 * Controllers Abstract Class | Controller\Controller.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Controller;

use Next\Application\Application;    # Applications Interface
use Next\HTTP\Request;               # HTTP Request Class
use Next\HTTP\Response;              # HTTP Request Interface
use Next\View\View;                  # View Engine Interface
use Next\Components\Object;          # Object Class

/**
 * Base structure for all Controllers to be used in association with an
 * Application created with Next Framework
 *
 * @package    Next\Controller
 *
 * @uses       Next\Application\Application
 *             Next\HTTP\Request
 *             Next\HTTP\Response
 *             Next\View\View
 *             Next\Components\Object
 */
abstract class Controller extends Object {

    /**
     * Application Object
     *
     * @var \Next\Application\Application $application
     */
    protected $application;

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
     * Session Manager
     *
     * @var \Next\Session\Manager $session
     */
    protected $session;

    /**
     * Controller Constructor.
     * Configures Controller Object with the Application Object provided, if any
     *
     * Application Objects are provided usually, but not restricted to, during
     * Dispatching Process
     *
     * @param \Next\Application\Application|optional $application
     *  Application Object
     */
    final public function __construct( Application $application = NULL ) {

        if( $application !== NULL ) {

            $this -> application = $application;

            // Request and Response Objects

            $this -> request  = $application -> getRequest();
            $this -> response = $application -> getResponse();

            // Application's View Engine

            $this -> view = $application -> getView();

            // Session Manager

            if( ( $session = $application -> getSession() ) !== NULL && session_status() == PHP_SESSION_ACTIVE ) {
                $this -> session = $session;
            }

            /**
             * @internal
             *
             * HTTP GET Parameters (a.k.a. Dynamic Parameters)
             * as Template Variables if a Next\View\View Engine has been provided
             */
            if( $this -> view instanceof View ) {
                $this -> view -> assign( $this -> request -> getQuery() );
            }

            // Constructing parent Object, which executes additional initialization routines

            parent::__construct();
        }
    }

    // Accessors

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
     * Get Response Object
     *
     * @return \Next\HTTP\Response
     *  Response Object
     */
    public function getResponse() : Response {
        return $this -> response;
    }

    /**
     * Get Application Object
     *
     * @return \Next\Application\Application|NULL
     *  Application Object if child classes have been fully constructed passing
     *  an Object to the Class Constructor and NULL otherwise
     */
    public function getApplication() : Application {
        return $this -> application;
    }

    // OverLoading

    /**
     * Check the EXISTENCE of an HTTP GET Parameter
     *
     * @param string $param
     *  Desired HTTP GET Parameter
     *
     * @return boolean
     *  TRUE if exists and FALSE otherwise
     */
    public function __isset( $param ) : bool {
        return ( array_key_exists( trim( $param ), $this -> request -> getQuery() ) );
    }

    /**
     * Retrieve an HTTP GET Parameter
     *
     * @param string $param
     *  Desired HTTP GET Parameter
     *
     * @return mixed|string
     *  Value of desired HTTP GET Parameter
     */
    public function __get( $param ) {
        return $this -> request -> getQuery( trim( $param ) );
    }

    /**
     * Sets a Template Variable from Controller Context instead of
     * using Next\View\View::assign() or through overloading,
     * if implemented
     *
     * @param string $param
     *  Template Variable Name
     *
     * @param string $value
     *  Template Variable Value
     */
    public function __set( $param, $value ) : void {
        $this -> view -> $param = $value;
    }

    /**
     * Unsets a Template Variable from Controller Context instead of
     * of through overloading — i.e. `unset( $this -> view -> variable )`
     *
     * @param string $param
     *  Desired Template Variable
     */
    public function __unset( $param ) : void {
        unset( $this -> view -> {$param} );
    }

    /**
     * Renders the Template View automatically based upon
     * Template View FileSpec, since a Template View Filename
     * can't be manually informed here
     */
    public function __destruct() {

        /**
         * @internal
         *
         * This checking is needed because when the DEVELOPMENT_MODE
         * constant is set as '2', case in which activates the
         * Routes Generator, this property is not a View Object yet,
         * because Controller Constructor did not receive a
         * `\Next\Application\Application` to work with
         */
        if( ( ! defined( 'DEVELOPMENT_MODE' ) || DEVELOPMENT_MODE < 2 ) &&
                $this -> view instanceof View ) {

            $this -> view -> render();
        }
    }
}
