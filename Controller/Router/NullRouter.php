<?php

namespace Next\Controller\Router;

use Next\Application\Application;    # Application Interface

/**
 * Null Router Class
 *
 * The Null Router accomplishes all interface and abstraction methods but in
 * fact doesn't do anything, useful when some component doesn't needs Routing,
 * like our built-in Exception Handlers
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class NullRouter extends AbstractRouter {

    /**
     * Finds a match Route
     *
     * @param Next\Application\Application $application
     *   Application to iterate Controllers
     *
     * @return boolean
     *   Always FALSE because this Router does nothing
     */
    public function find( Application $application ) {
        return FALSE;
    }

    // Parameterizable Interface Methods Implementation

    /**
     * Set Child Classes Options
     */
    public function setOptions() {}

    // Abstract Methods Implementation

    /**
     * Establishes a Connection
     *
     * Establishes a Connection with a Database, with a File (through a Stream)...
     */
    protected function connect() {}

    /**
     * Lookup for Required Params in URL
     *
     * @param array $params
     *   Array of Params to validate
     *
     * @param string $uri
     *   Request URI to be checked against
     *
     * @param array|optional $queryData
     *   Manually set GET parameters to be considered as validatable arguments too
     */
    protected function lookup( array $params, $uri, array$queryData = array() ) {}

    /**
     * Check if Required Parameters are present
     *
     * Also check if they are valid in a list of valid values, if defined
     *
     * @param array $params
     *   Route Params
     *
     * @param string $URI
     *   Route URI
     */
    protected function validate( array $params, $URI ) {}

    /**
     * Process Dynamic Params
     *
     * @param array $params
     *   Array of Params to parse
     *
     * @param string $URI
     *   Request URI to be checked against
     */
    protected function process( array $params, $URI ) {}
}