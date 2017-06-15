<?php

/**
 * Controller Routers Interface | Controller\Router\Router.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
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
     * Finds a match Route
     *
     * @param \Next\Application\Application $application
     *  Application to iterate Controllers
     */
    public function find( Application $application );

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
