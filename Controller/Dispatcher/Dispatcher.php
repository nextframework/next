<?php

/**
 * Controllers Dispatchers Interface | Controller\Dispatcher\Dispatcher.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Controller\Dispatcher;

use Next\Application\Application;    # Application Interface

use Next\Components\Parameter;       # Parameter Class

/**
 * Defines the Dispatcher Type, with all methods that must be present
 * in an Dispatcher, be it through \Next\Controller\Dispatcher\AbstractDispatcher
 * or the concrete implementations of it
 *
 * @package    Next\Controller\Dispatcher
 */
interface Dispatcher {

    /**
     * Dispatches the Controller
     *
     * @param \Next\Application\Application $application
     *  Application to Configure
     *
     * @param \Next\Components\Parameter $data
     *  Parameters to Configure Application
     */
    public function dispatch( Application $application, Parameter $data );
}
