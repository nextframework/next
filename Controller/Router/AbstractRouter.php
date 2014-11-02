<?php

namespace Next\Controller\Router;

use Next\Components\Interfaces\Parameterizable;    # Parameterizable Interface
use Next\Components\Parameter;                     # Parameter Class

/**
 * Controller Router Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractRouter implements Parameterizable, Router {

    /**
     * Dynamic GET Params
     *
     * @var array $params
     */
    protected $params = array();

    /**
     * Default Options
     *
     * Must be overwritten
     *
     * @var array $defaultOptions
     */
    private $defaultOptions = array();

    /**
     * Routers Options
     *
     * @var Next\Components\Parameter $options
     */
    protected $options;

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
     * @param mixed|optional $options
     *
     *   <br />
     *
     *   <p>List of Options to affect Routers. Acceptable values are:</p>
     *
     *   <p>
     *
     *       <ul>
     *
     *           <li>Associative and multidimensional array</li>
     *
     *           <li>
     *
     *               An {@link http://php.net/manual/en/reserved.classes.php stdClass Object}
     *
     *           </li>
     *
     *           <li>A well formed Parameter Object</li>
     *
     *       </ul>
     *
     *   </p>
     *
     *   <p>There are no Common Options defined so far.</p>
     *
     *   <p>
     *       All the arguments taken in consideration are defined in
     *       (and by) concrete classes
     *   </p>
     *
     * @see Next\Components\Parameter
     */
    public function __construct( $options = NULL ) {

        // Setting Up Options Object

        $this -> options = new Parameter( $this -> defaultOptions, $this -> setOptions(), $options );

        // Calling the Connector, which could be to a Database, a File Stream, a XML...

        $this -> connect();

        // Extra Initialization

        $this -> init();
    }

    /**
     * Additional Initialization. Must be overwritten
     */
    protected function init() {}

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

    // Abtsrcat Methods Definition

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
