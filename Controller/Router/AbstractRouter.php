<?php

namespace Next\Controller\Router;

use Next\Components\Object;    # Object Class

/**
 * Controller Router Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractRouter extends Object implements Router {

    /**
     * Default Options
     *
     * @var array $defaultOptions
     */
    private $defaultOptions = array();

    /**
     * Dynamic GET Params
     *
     * @var array $params
     */
    protected $params = array();

    /**
     * Match Controller
     *
     * @var string $controller
     */
    protected $controller;

    /**
     * Match Action
     *
     * @var string $action
     */
    protected $action;

    /**
     * Router Constructor
     *
     * @param mixed|Next\Components\Object|Next\Components\Parameter|stdClass|array|optional $options
     *  Configuration Options for Router Strategy
     *
     * @see Next\Components\Parameter
     */
    public function __construct( $options = NULL ) {
        parent::__construct( $this -> defaultOptions, $options );
    }

    // Accessors

    /**
     * Additional Initialization
     * Calls the Router Connector, which could be to a Database, a File Stream, a XML...
     */
    protected function init() {
        $this -> connect();
    }

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
     * Get match Action
     *
     * @return string
     *  Match Action Method
     */
    public function getAction() {
        return $this -> action;
    }

    // Parameterizable Interface Methods Implementation

    /**
     * Get Router Options
     *
     * @return Next\Components\Parameter
     *  Parameter Object with merged options
     */
    public function getOptions() {
        return $this -> options;
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
    abstract protected function lookup( array $params, $uri, array $queryData = array() );

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
