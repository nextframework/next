<?php

/**
 * HTTP Request Router Abstract Class | HTTP\Router\AbstractRouter.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Router;

use Next\Components\Object;    # Object Class

/**
 * Defines the base structure for an HTTP Request Router
 *
 * @package    Next\HTTP
 */
abstract class AbstractRouter extends Object implements Router {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'application' => [ 'type' => 'Next\Application\Application', 'required' => TRUE ]
    ];

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

    // Routing Flow-related Methods

    /**
     * Sets the Router to abort its flow, not routing anything,
     * so the Front Controller can keep going
     *
     * @return \Next\HTTP\Router\Router
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
     * Get matching Controller
     *
     * @return string
     *  Match Controller Class
     */
    public function getController() {
        return $this -> controller;
    }

    /**
     * Get matching Action Method
     *
     * @return string
     *  Match Action Method
     */
    public function getMethod() {
        return $this -> method;
    }

    // Abstract Methods Definition

    /**
     * Lookup for Required Params in URL
     *
     * @param array $params
     *  Array of Params to validate
     *
     * @param string $uri
     *  Request URI to be checked against
     */
    abstract protected function lookup( array $params, $uri );

    /**
     * Checks if Required Parameters are present and if they are
     * valid in a list of valid values, if defined
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
