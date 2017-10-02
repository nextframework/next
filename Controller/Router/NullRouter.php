<?php

/**
 * Controller "Null" Router Class | Controller\Router\NullRouter.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Controller\Router;

use Next\Application\Application;    # Application Interface

/**
 * The Null Router accomplishes all interface and abstraction methods
 * but in fact doesn't do anything.
 *
 * It's useful when some resource doesn't need routing but may need
 * to abort the Response Flow
 *
 * @package    Next\Components\Router
 */
class NullRouter extends AbstractRouter {

    // Router Interface Method Implementation

    /**
     * Finds a matching Route for the Application -AND- current Request URI
     *
     * @return boolean
     *  Always FALSE because this Router does nothing
     */
    public function find() {
        return FALSE;
    }

    // Abstract Methods Implementation

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
    protected function lookup( array $params, $uri, array$queryData = [] ) {}

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
    protected function validate( array $params, $URI ) {}

    /**
     * Process Dynamic Params
     *
     * @param array $params
     *  Array of Params to parse
     *
     * @param string $URI
     *  Request URI to be checked against
     */
    protected function process( array $params, $URI ) {}
}