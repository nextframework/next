<?php

namespace Next\Application;

use Next\HTTP\Request;     # Request Class
use Next\HTTP\Response;    # Response Class

/**
 * Application Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Application {

    /**
     * Get Application Directory
     *
     * Application directory comes from Application Class NameSpace
     */
    public function getApplicationDirectory();

    /**
     * Get Controllers Chain
     *
     * Get all Controller Objects associated to Application
     */
    public function getControllers();

    /**
     * Get View Engine
     */
    public function getView();

    /**
     * Get Router
     */
    public function getRouter();

    /**
     * Set Request Object
     *
     * @param Next\HTTP\Request $request
     *   Request Object
     */
    public function setRequest( Request $request );

    /**
     * Get Request Object
     */
    public function getRequest();

    /**
     * Set Response Object
     *
     * @param Next\HTTP\Response $response
     *   Response Object
     */
    public function setResponse( Response $response );

    /**
     * Get Response Object
     */
    public function getResponse();
}
