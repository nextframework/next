<?php

/**
 * Application Abstract Class | Application/AbstractApplication.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Application;

use Next\HTTP\Request;     # Request Class
use Next\HTTP\Response;    # Response Class

/**
 * Defines the Application Type, with all methods that must be present
 * in an Application, be it through \Next\Application\AbstractApplication
 * or the concrete implementations of it
 *
 * @package    Next\Application
 */
interface Application {

    /**
     * Get Application Directory
     *
     * Application directory comes from Application Class NameSpace
     */
    public function getApplicationDirectory();

    /**
     * Set Request Object
     *
     * @param \\Next\HTTP\Request $request
     *  Request Object
     */
    public function setRequest( Request $request );

    /**
     * Get Request Object
     */
    public function getRequest();

    /**
     * Set Response Object
     *
     * @param \Next\HTTP\Response $response
     *  Response Object
     */
    public function setResponse( Response $response );

    /**
     * Get Response Object
     */
    public function getResponse();

    /**
     * Get Router
     */
    public function getRouter();

    /**
     * Get View Engine
     */
    public function getView();

    /**
     * Get Controllers Chain
     *
     * Get all Controller Objects associated to Application
     */
    public function getControllers();

    /**
     * Get Caching Schema Chain
     */
    public function getCache();
}
