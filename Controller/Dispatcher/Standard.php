<?php

namespace Next\Controller\Dispatcher;

use Next\Components\Debug\Exception;        # Exception Class
use Next\Controller\ControllerException;    # Controller Exception Class
use Next\View\ViewException;                # View Exception Class

use Next\Application\Application;           # Application Interface

use Next\Components\Debug\Handlers;         # Exceptions Handlers Class
use Next\Components\Parameter;              # Parameter Class

use Next\HTTP\Response;                     # HTTP Response Class

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
     * @param Next\Components\Parameter $data
     *  Parameters to Configure Application
     *
     * @return Next\HTTP\Response
     *  Response Object
     *
     * @throws Next\Controller\Dispatcher\DispatcherException
     *  ReflectionException was caught
     */
    public function dispatch( Application $application, Parameter $data ) {

        $response = $application -> getResponse();

        try {

            $this -> setDispatched( TRUE );

            // Adding Request Params

            $application -> getRequest() -> setQuery(
                (array) $data -> getParameters() -> params -> getParameters()
            );

            // Calling Action from Controller of defined Application

            $reflector = new \ReflectionMethod( $data -> controller, $data -> method );

            $reflector -> invoke( new $data -> controller( $application ) );

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
             * If the caught Exception is not a severe error, like a query
             * returning no results, instead of the __EXCEPTION__, a template variable
             * named __INFO__ will be created
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
             * errors triggered by Next\View\View concrete classes, specially
             * when they come from Magic Methods which is directly related
             * to Template Variables usage.
             *
             * And by forcing a Development Handler we warn lazy programmers
             * they are doing the wrong thing, like trying to hide the error ^_^
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

            /**
             * @internal
             *
             * List of non-error HTTP Codes
             *
             * @var $codes array
             */
            $codes = array(

                Response::OK, Response::CREATED, Response::ACCEPTED,
                Response::NO_CONTENT, Response::PARTIAL_CONTENT, Response::NOT_MODIFIED
            );

            $view = $application -> getView();

            if( in_array( $e -> getResponseCode(), $codes ) ) {

                $view -> assign( '__INFO__', $e -> getMessage() );

            } else {

                $view -> assign( '__EXCEPTION__', $e -> getMessage() );
            }

            $callback = $e -> getCallback();

            if( is_callable( $callback ) ) {
                return call_user_func( $callback );
            }

            switch( count( $callback ) ) {

                case 0:
                    // No callback, do nothing
                break;
                case 1:  call_user_func( $callback[ 0 ] ); break;
                case 2:  call_user_func( $callback[ 0 ], $callback[ 1 ] ); break;
                default:

                    if( ! is_callable( $callback[ 0 ] ) ) {

                        Handlers::development(
                            new Exception( 'Exception callbacks must be callable' )
                        );
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
             *
             * Note that this won't do any magic and handle a ControllerException
             * thrown in a method that's being accessed through a previously handled
             * ControllerException (callback).
             *
             * This WILL end up being a infinite recursion
             */
            return $this -> handleExceptionCallback( $application, $e );

        } catch( ViewException $e ) {

            /**
             * @internal
             * Same rules for when a ViewException is triggered during the
             * dispatching process, except here a ViewException is thrown
             * while handling any ControllerException thrown
             */
            if( ob_get_length() ) {

                // We want ONLY the Exception Template

                ob_end_clean();
            }

            Handlers::development( $e );
        }
    }
}