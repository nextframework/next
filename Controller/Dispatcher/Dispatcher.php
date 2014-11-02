<?php

namespace Next\Controller\Dispatcher;

use Next\Application\Application;    # Application Interface

/**
 * Controller Dispatcher Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Dispatcher {

    /**
     * Dispatches the Controller
     *
     * @param Next\Application\Application $application
     *  Application to Configure
     *
     * @param stdClass $data
     *  Data to Configure Application
     */
    public function dispatch( Application $application, \stdClass $data );
}
