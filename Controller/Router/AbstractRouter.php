<?php

/**
 * Controller Router Abstract Class | Controller\Router\AbstractRouter.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Controller\Router;

use Next\Application\Application;    # Application Interface
use Next\Components\Object;          # Object Class

/**
 * Defines the base structure for a Controller Router
 *
 * @package    Next\Controller\Router
 */
abstract class AbstractRouter extends Object implements Router {

    /**
     * Application Object
     *
     * @var \Next\Application\Application $application
     */
    protected $application;

    /**
     * Flag to condition whether or not the Router will do its job
     *
     * @var boolean $shouldRoute
     */
    protected $shouldRoute = TRUE;

    /**
     * Dynamic GET Params
     *
     * @var array $params
     */
    protected $params = [];

    /**
     * Match Controller
     *
     * @var string $controller
     */
    protected $controller;

    /**
     * Match Action
     *
     * @var string $method
     */
    protected $method;

    /**
     * Constructor Overwriting
     * Sets up a type-hinted Application Object for all Routing Strategies
     *
     * @param \Next\Application\Application $application
     *  Application Object
     *
     * @param mixed|\Next\Components\Object|\Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for Router Adapter
     */
    public function __construct( Application $application, $options = NULL ) {

        parent::__construct( $options );

        $this -> application = $application;
    }

    /**
     * Additional Initialization
     * Calls the Router Connector, which could be to a Database, a File Stream, a XML...
     */
    protected function init() {
        $this -> connect();
    }

    // Routing Flow-related Methods

    /**
     * Sets the Router to abort its flow, not routing anything,
     * so the Front Controller can keep going
     *
     * @return \Next\Controller\Router\Router
     *  Router Object (Fluent Interface)
     */
    public function abortFlow() {

        $this -> shouldRoute = FALSE;

        return $this;
    }

    /**
     * Gets the current state of Routing Flow flag
     *
     * @return boolean
     *  Current state of Routing Flow flag
     */
    public function shouldRoute() {
        return $this -> shouldRoute;
    }

    // Accessors

    /**
     * Get match Controller
     *
     * @return string
     *  Match Controller Class
     */
    public function getController() {
        return $this -> controller;
    }

    /**
     * Get match Action Method
     *
     * @return string
     *  Match Action Method
     */
    public function getMethod() {
        return $this -> method;
    }

    // Abstract Methods Definition

    /**
     * Establishes a Connection
     *
     * Establishes a Connection with a Database, with a File (through a Stream)...
     */
    abstract protected function connect();

    /**
     * Lookup for Required Params in URL
     *
     * @param array $params
     *  Array of Params to validate
     *
     * @param string $uri
     *  Request URI to be checked against
     *
     * @param array|optional $queryData
     *  Manually set GET parameters to be considered as validatable arguments too
     */
    abstract protected function lookup( array $params, $uri, array $queryData = [] );

    /**
     * Check if Required Parameters are present
     *
     * Also check if they are valid in a list of valid values, if defined
     *
     * @param array $params
     *  Route Params
     *
     * @param string $URI
     *  Route URI
     */
    abstract protected function validate( array $params, $URI );

    /**
     * Process Dynamic Params
     *
     * @param array $params
     *  Array of Params to parse
     *
     * @param string $URI
     *  Request URI to be checked against
     */
    abstract protected function process( array $params, $URI );
}
