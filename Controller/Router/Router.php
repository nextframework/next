<?php

/**
 * Controller Routers Interface | Controller\Router\Router.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Controller\Router;

use Next\Application\Application;    # Application Interface

/**
 * Defines the Router Type, with all methods that must be present
 * in an Router, be it through \Next\Controller\Router\AbstractRouter
 * or the concrete implementations of it
 *
 * @package    Next\Controller\Router
 */
interface Router {

    /**
     * Finds a matching Route for the Application -AND- current Request URI
     */
    public function find();

    // Accessors

    /**
     * Get match Controller
     */
    public function getController() ;

    /**
     * Get match Action Method
     */
    public function getMethod();
}
