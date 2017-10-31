<?php

/**
 * Controller Front Class | Controller\Front.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Controller;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\BadMethodCallException;
use Next\HTTP\Router\RouterException;

use Next\Components\Object;             # Object Class
use Next\Controller\Dispatcher;         # Standard Dispatcher Class
use Next\Exception\ExceptionHandler;    # Exceptions Handlers Class
use Next\HTTP\Request;                  # Request Class
use Next\HTTP\Response;                 # Response Class

/**
 * The Front Controller iterates through all Applications in a Chain trying
 * to match, through their associated Router, if any, a Page Controller to
 * dispatch
 *
 * It's one of the most busy classes in the Routing/Dispatching process:
 *
 * - Iterates through all Application added to the Applications Chain
 * - Executes all Caching Schemas added to their Caching Schemas' Chain
 * - Communes with their associated Routers until one of them becomes able to
 *   handle current Request, checking if the Request should be dispatched
 *   or returned "as is"
 * - Deals with the Dispatcher, checking if the associated Response Object
 *   it should flush its Response Body or be returned it for later
 * - And if anything at fails in the process it deals with  the ExceptionHandler
 *   to create an Error Response or then send 503 or 404 Headers to the browser
 *
 * @package    Next\Controller
 *
 * @uses       Next\Exception\Exceptions\BadMethodCallException
 *             Next\HTTP\Router\RouterException
 *             Next\Components\Object
 *             Next\Controller\Dispatcher
 *             Next\Exception\ExceptionHandler
 *             Next\HTTP\Request
 *             Next\HTTP\Response
 */
class Front extends Object {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'applications' => [ 'type' => 'Next\Application\Chain', 'required' => TRUE ]
    ];

    /**
     * Controllers' Dispatcher
     *
     * @var \Next\Controller\Dispatcher $dispatcher
     */
    protected $dispatcher;

    /**
     * Additional Initialization.
     * Instantiates the Controllers' Dispatcher
     */
    protected function init() : void {
        $this -> dispatcher = new Dispatcher;
    }

    /**
     * Dispatches a Controller
     *
     * @return \Next\HTTP\Response|boolean|mixed
     *  Returns FALSE if current Next\Application\Application` in iteration
     *  doesn't have an associated Next\HTTP\Router` -OR- if it does have one
     *  but it has been defined to not route anything or at least not anymore
     *
     *  Otherwise return the `Next\HTTP\Response` Object returned by the
     *  `Next\Controller\Dispatcher`
     *
     *  If a `Next\Controller\ControllerException` is caught during the
     *  dispatching process, case in which the Standardization Error Concept
     *  takes place, whatever the callback associated to the Exception thrown
     *  is returned instead
     */
    public function dispatch() {

        foreach( $this -> options -> applications as $application ) {

            $response = $application -> getResponse();
            $router   = $application -> getRouter();

            // Running Caching Schemas, if any

            foreach( $application -> getCache() as $schema ) $schema -> run();

            // Aborting the flow if there's no Router assigned for the Application

            if( $router === FALSE ) return FALSE;

            // Leaving the Flow if we shouldn't route (i.e. direct access to files)

            if( ! $router -> shouldRoute() ) return FALSE;

            try {

                if( ( $match = $router -> find() ) !== NULL ) {

                    /**
                     * @internal
                     * Dispatching Controller, if nothing was wrongly
                     * dispatched before
                     */
                    if( ! $this -> dispatcher -> isDispatched() ) {

                        try {

                            $dispatched = $this -> dispatcher
                                                -> dispatch( $application, $match );

                            // Should we return what was dispatched?

                            if( $this -> dispatcher -> shouldReturn() ) {
                                return $dispatched;
                            }

                        } catch( BadMethodCallException $e ) {

                            /**
                             *  BadMethodCallException are thrown when
                             *  something irreversible happened when
                             *  dispatching the Controller, which means
                             *  we can safely use an HTTP Code 503
                             *
                             * But we'll condition this to the internal
                             * constant DEVELOPMENT MODE. If it is defined
                             * and its value is greater than '1' we'll
                             * send DEvelopment Exception so the developer
                             * can, possibly, understand why this is happening
                             */
                            if( ( defined( 'DEVELOPMENT_MODE' ) && DEVELOPMENT_MODE >= 1 ) ) {
                                ExceptionHandler::development( $e, 503 );
                            } else {
                                ExceptionHandler::response( 503 );
                            }
                        }

                        // So far, so good. Let's try to send the Response

                            // Should we return the Response?

                        if( $response -> shouldReturn() ) {
                            return $response;
                        }

                        $response -> send();
                    }
                }

            } catch( RouterException $e ) {

                $this -> dispatcher -> setDispatched( TRUE );

                /**
                 * @internal
                 * RouterException is thrown only by Application's Router
                 * when validating parameters against Request URI, by searching
                 * for missing required parameters (if any) or mal-formed
                 * parameters
                 */
                ExceptionHandler::production( $e );
            }
        }

        /**
         * @internal
         * No Controllers were able to handle the Request? So NOT FOUND
         *
         * Instead of send 404 header we'll display the Error Template File
         * only if we still allowed to do it
         */
        if( ! $this -> dispatcher -> isDispatched() &&
            ! $this -> dispatcher -> shouldReturn() ) {

            ExceptionHandler::response( 404 );
        }
    }
}