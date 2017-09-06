<?php

/**
 * Controllers Abstract Class | Controller\AbstractController.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Controller;

use Next\HTTP\Request\RequestException;    # HTTP Request Exception
use Next\View\ViewException;               # View Exception
use Next\View\View;                        # View Engine Interface
use Next\Application\Application;          # Applications Interface
use Next\Components\Object;                # Object Class

/**
 * Defines the base structure for a Controller to be used in
 * conjunction with \Next\Application\Application created with Next Framework
 *
 * @package    Next\Controller
 */
abstract class AbstractController extends Object implements Controller {

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
     * Controller Constructor
     * Configures Controller Object with the Application Object provided, if any
     *
     * Application Objects are provided usually, but not restricted to, during Dispatching Process
     *
     * @param \Next\Application\Application|optional $application
     *  Application Object
     *
     * @param mixed|optional $options
     *  Additional options
     */
    final public function __construct( Application $application = NULL, $options = NULL ) {

        if( ! is_null( $application ) ) {

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

            // HTTP GET Params (a.k.a. Dynamic Params) as Template Variables

            $this -> view -> assign( $this -> request -> getQuery() );

            // Constructing parent Object, which executes additional initialization routines

            parent::__construct( $options );
        }
    }

    // Accessors

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
     * Get Response Object
     *
     * @return \Next\HTTP\Response
     *  Response Object
     */
    public function getResponse() {
        return $this -> response;
    }

    /**
     * Get Application Object
     *
     * @return \Next\Application\Application|NULL
     *  Application Object if child classes have been fully constructed passing
     *  an Object to the Class Constructor and NULL otherwise
     */
    public function getApplication() {
        return $this -> application;
    }

    // OverLoading

    /**
     * Check for GET Params existence
     *
     * @note The PRESENCE of key is tested, not its value
     *
     * @param string $param
     *  Desired Param from Dynamic Params
     *
     * @return boolean
     *  TRUE if exists, FALSE otherwise OR if a
     *  \Next\HTTP\Request\RequestException is caught
     *
     * @throws \Next\Controller\ControllerException
     *  Testing existence of internal properties
     */
    public function __isset( $param ) {

        $param = trim( $param );

        if( substr( $param, 0, 1 ) == '_' ) {

            throw ControllerException::unnecessaryTest();
        }

        try {

            return ( $this -> request -> getQuery( $param ) !== FALSE );

        } catch( RequestException $e ) {

            return FALSE;
        }
    }

    /**
     * Retrieve a GET Param
     *
     * Grant access to a Request Dynamic Params using Property Notation instead Array Notation
     *
     * @param string $param
     *  Desired Param from Dynamic Params
     *
     * @return mixed Dynamic Param Value
     *
     * @throws \Next\Controller\ControllerException
     *  Trying to access internal properties prefixed with an underscore
     *  without use their correct accessors
     *
     * @throws \Next\Controller\ControllerException
     *  Trying to access non-existent param
     */
    public function __get( $param ) {

        $param = trim( $param );

        try {

            return $this -> request -> getQuery( $param );

        } catch( RequestException $e ) {

            throw ControllerException::paramNotFound($e);
        }
    }

    /**
     * Set a Template Variable
     *
     * <p>
     *     Allows to set a Template Variable directly from Controller
     *     context, instead of using \Next\View\View::assign() or
     *     \Next\View\View::__set() (if implemented)
     * </p>
     *
     * <p>This method is slightly different of others.</p>
     *
     * <p>
     *     While __get() and __isset() work over the HTTP GET Params
     *     (a.k.a Dynamic Params) this one works over Template Variables
     * </p>
     *
     * @param string $param
     *  Template Variable Name
     *
     * @param string $value
     *  Template Variable Value
     *
     * @throws \Next\Controller\ControllerException
     *  A \Next\View\ViewException was caught due a Template Variable
     *  forbiddenness, because it conflicts with a reserved (or internal)
     *  Template Variable name
     */
    public function __set( $param, $value ) {

        try {

            $this -> view -> $param = $value;

        } catch( ViewException $e ) {

            throw ControllerException::assignmentFailure( $e );
        }
    }

    /**
     * Unset a Template Variable
     *
     * Allows to unset a Template Variable from Controller context,
     * instead of using:
     *
     * <code>
     *   $this -> view -> remove( 'variable' );
     *
     *   // OR
     *
     *  unset( $this -> view -> variable );
     * </code>
     *
     * This method is slightly different of others. While __get() and __isset()
     * work over the HTTP GET Params (a.k.a Dynamic Params) this one works over
     * Template Variables
     *
     * @param string $param
     *  Desired Template Variable
     *
     * @throws \Next\Controller\ControllerException
     *  Trying to unset an forbidden Template Variable,
     *  always prefixed with an unserscore.
     *
     * @throws \Next\Controller\ControllerException
     *  Trying to unset a nonexistent Template Variable
     */
    public function __unset( $param ) {

        try {

            unset( $this -> view -> $param );

        } catch( ViewException $e ) {

            throw ControllerException::removalFailure( $e );
        }
    }

    /**
     * Render Template View automatically
     *
     * <p>
     *     Render Template View automatically based upon
     *     Template View FileSpec, once a Template View Filename
     *     cannot be manually informed
     * </p>
     */
    public function __destruct() {

        /**
         * @internal
         * This checking is needed because when the DEVELOPMENT_MODE constant
         * is set as '2', case in which activates the Routes Generator, this
         * property is not a View Object yet, because Controller Constructor
         * did not receive an Application to work with
         *
         * This checking has no effects when this constant is set as 1 or 0 (zero).
         */
        if( $this -> view instanceof View ) {

            $this -> view -> render();
        }
    }
}
