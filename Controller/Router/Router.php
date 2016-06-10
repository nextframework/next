<?php

namespace Next\Controller\Router;

use Next\Components\Interfaces\Parameterizable;    # Parameterizable Interface
use Next\Application\Application;                  # Application Interface

/**
 * Controller Router Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Router extends Parameterizable {

    /**
     * Finds a match Route
     *
     * @param Next\Application\Application $application
     *  Application to iterate Controllers
     */
    public function find( Application $application );

    // Accessors

    /**
     * Get match Controller
     */
    public function getController() ;

    /**
     * Get match Action
     */
    public function getAction();
}
