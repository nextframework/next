<?php

/**
 * HTTP Request Routers Interface | HTTP\Router\Router.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Router;

use Next\Application\Application;    # Application Interface

/**
 * Defines the Router Type, with all methods that must be present
 * in an Router, be it through \Next\HTTP\Router\AbstractRouter
 * or the concrete implementations of it
 *
 * @package    Next\HTTP
 */
interface Router {

    /**
     * Finds a matching Route for the Application -AND- current Request URI
     */
    public function find();

    // Accessors

    /**
     * Get matching Controller
     */
    public function getController() ;

    /**
     * Get matching Action Method
     */
    public function getMethod();
}
