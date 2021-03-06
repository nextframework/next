<?php

/**
 * HTTP Request "Null" Router Class | HTTP\Router\NullRouter.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Router;

use Next\Components\Parameter;    # Parameter Class

/**
 * The Null Router accomplishes all interface and abstraction methods
 * implementations but in fact doesn't do anything.
 *
 * It's useful when some resource doesn't need routing but may need
 * to abort the Response Flow, respecting PHP 7 Return Type Declaration
 *
 * @package    Next\HTTP
 *
 * @uses       Next\Components\Parameter
 *             Next\HTTP\Router\Router
 */
class NullRouter extends Router {

    /**
     * Finds a match Route
     *
     * @return boolean
     *  Always FALSE because this Router does nothing
     */
    public function find() :? Parameter {
        return NULL;
    }

    // Abstract Methods Implementation

    /**
     * Establishes a Connection
     *
     * Establishes a Connection with a Database, with a File (through a Stream)...
     */
    protected function connect() : void {}

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
    protected function lookup( array $params, $uri, array$queryData = array() ) : void {}

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
    protected function validate( array $params, $URI ) : void {}

    /**
     * Process Dynamic Params
     *
     * @param array $params
     *  Array of Params to parse
     *
     * @param string $URI
     *  Request URI to be checked against
     */
    protected function process( array $params, $URI ) : void {}
}