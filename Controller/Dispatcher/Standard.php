<?php

namespace Next\Controller\Dispatcher;

use Next\Components\Debug\Exception;        # Exception Class
use Next\Controller\ControllerException;    # Controller Exception
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

        $response = $application -> getResponse();

        try {

            $this -> setDispatched( TRUE );

            // Adding Request Params

            $application -> getRequest() -> setQuery( $data -> params );

            // Calling Action from Controller at defined Application

            $reflector = new \ReflectionMethod( $data -> class, $data -> method );

            $reflector -> invoke( new $data -> class( $application ) );

            return $response;

        } catch( \ReflectionException $e ) {

            throw DispatcherException::reflection( $e );

        } catch( ControllerException $e ) {

            /**
             * @internal
             * ControllerException come from Application Controllers
             * and, as part of Error Standardization Concept, should be thrown when
             * something is wrong
             *
             * E.g.: Database Query results in FALSE instead of a Rowset Object
             *
             * Doesn't matter the level of DEVELOPMENT MODE Constant, we'll
             * try to create a Template Variable and virtually re-send the Response,
             * by invoking a callback previously associated to ControllerException object
             *
             * Now, in Template View, a special variable named __EXCEPTION__ will
             * be available with Exception Message just like if it was assigned
             * from Controller context
             *
             * If the assignment or rendering fails (unlikely), the Production Handler
             * will be used as fallback
             */
            try {

                $response -> addHeader( $e -> getResponseCode() );

            } catch( FieldsException $e ) {}

            try {

                return $this -> handleExceptionCallback( $application, $e );

            } catch( ViewException $e ) {

                Handlers::production( $e );
            }

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

    // Auxiliary Methods

    /**
     * Handles a dispatchable Exception Callback
     *
     * @param  Next\Application\Application $application
     *  Application Object being dispatched
     *
     * @param  Next\Components\Debug\Exception $e
     *  Exception thrown
     *
     * @return void
     */
    private function handleExceptionCallback( Application $application, Exception $e ) {

        try {

            $application -> getView() -> assign( '__EXCEPTION__', $e -> getMessage() );

            $callback = $e -> getCallback();

            if( is_callable( $callback ) ) {
                return call_user_func( $callback );
            }

            switch( count( $callback ) ) {

                case 1:  call_user_func( $callback[ 0 ] ); break;
                case 2:  call_user_func( $callback[ 0 ], $callback[ 1 ] ); break;
                default:

                    if( ! is_callable( $callback[ 0 ] ) ) {
                        Handlers::development( new Exception( 'Exception callbacks must be callable' ) );
                    }

                    call_user_func_array( $callback[ 0 ], array_slice( $callback, 1 ) );

                break;
            }

        } catch( ControllerException $e ) {

            /**
             * @internal
             * If a ControllerException is caught here we handle any nested
             * ControllerException existing within the process
             *
             * This allows, for example, an Standardized Error that occurs in a
             * specific action of dispatched Application be virtually redirected
             * to the previous action page without need to repeat all the code
             * related to this page
             */
            return $this -> handleExceptionCallback( $application, $e );
        }
    }
}