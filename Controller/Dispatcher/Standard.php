<?php

namespace Next\Controller\Dispatcher;

use Next\View\ViewException;                # View Exception Class
use Next\Application\Application;           # Application Interface
use Next\Components\Debug\Handlers;         # Exceptions Handlers

/**
 * Standard Controller Dispatcher Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Standard extends AbstractDispatcher {

    // Interface Method Implementation

    /**
     * Dispatches the Controller
     *
     * @param Next\Application\Application $application
     *  Application to Configure
     *
     * @param stdClass $data
     *  Data to Configure Application
     *
     * @return Next\HTTP\Response
     *  Response Object
     *
     * @throws Next\Controller\Dispatcher\DispatcherException
     *  ReflectionException was caught
     */
    public function dispatch( Application $application, \stdClass $data ) {

        try {

            $this -> setDispatched( TRUE );

            // Adding Request Params

            $application -> getRequest() -> setQuery( $data -> params );

            // Calling Action from Controller at defined Application

            $reflector = new \ReflectionMethod( $data -> class, $data -> method );

            $reflector -> invoke( new $data -> class( $application ) );

            return $application -> getResponse();

        } catch( \ReflectionException $e ) {

            throw DispatcherException::reflection( $e );

        } catch( ViewException $e ) {

            /**
             * @internal
             * Catching ViewException grants a nice view for any sort of
             * errors triggered by Next View Class, specially when they come from Magic Methods
             * which is directly related to Template Variables usage.
             *
             * And by forcing a Development Handler we warn lazy
             * programmers they are doing the wrong thing, like trying to hide the error ^^
             */
            if( ob_get_length() ) {

                // We want ONLY the Exception Template

                ob_end_clean();
            }

            Handlers::development( $e );
        }
    }
}