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

use Next\Controller\Router\RouterException;            # Router Exception Class
use Next\Controller\Dispatcher\DispatcherException;    # Dispatcher Exception Class

use Next\Components\Object;                            # Object Class
use Next\Controller\Dispatcher\Standard;               # Standard Dispatcher Class

use Next\Components\Debug\Handlers;                    # Errors & Exceptions Handlers

use Next\HTTP\Request;                                 # Request Class
use Next\HTTP\Response;                                # Response Class

/**
 * The Front Controller Class, one of the most busy classes in the
 * Routing/Dispatching process:
 *
 * - Iterates through all \Next\Application\Application added to \Next\Application\Chain;
 * - Executes all \Next\Cache\Schema\Schema added to their \Next\Cache\Schema\Chain;
 * - Checks with their \Next\Controller\Router\Router if current
 *   Request should be routed or delivered "as is";
 * - Communes with their Routers until one of them becomes able to
 *   handle current Request;
 * - Deals with their associated \Next\Controller\Dispatcher\Dispatcher,
 *   checking if its \Next\HTTP\Response should output a Response Body
 *   or return it for later;
 * - And if anything at fail in the process deal with \Next\Components\Debug\Handlers
 *   to create an Error Response or then send 503 or 404 Headers
 *   to the browser;
 *
 * @package    Next\Controller
 */
class Front extends Object {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [

        'applications' => [ 'type' => 'Next\Application\Chain', 'required' => TRUE ],
        'dispatcher'   => [ 'type' => 'Next\Controller\Dispatcher\Dispatcher', 'required' => FALSE ]
    ];

    /**
     * Additional Initialization.
     * Assigns a default Controller Dispatcher if none has been provided
     */
    protected function init() {

        // Setting up a default Dispatcher Object

        if( $this -> options -> dispatcher === NULL ) {
            $this -> options -> dispatcher = new Standard;
        }
    }

    /**
     * Dispatches a Controller
     *
     * @return mixed|void
     *  Returns what the chosen Dispatcher have dispatched,
     *  if configured to do so
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

                // Trying to find a matching Route

                $match = $router -> find(/* $application */);

                if( $match !== FALSE ) {

                    /**
                     * @internal
                     * Dispatching Controller, if nothing was wrongly
                     * dispatched before
                     */
                    if( ! $this -> options -> dispatcher -> isDispatched() ) {

                        try {

                            $dispatched = $this -> options
                                                -> dispatcher
                                                -> dispatch( $application, $match );

                            // Should we return what was dispatched?

                            if( $this -> options -> dispatcher -> shouldReturn() ) {
                                return $dispatched;
                            }

                        } catch( DispatcherException $e ) {

                            /**
                             *  If a DispatcherException should be
                             *  thrown only when something irreversible
                             *  happens it means we can safely use an
                             *  HTTP Code 503 (Service Unavailable)
                             *
                             * But we'll condition this to the internal
                             * constant DEVELOPMENT MODE. If it is defined
                             * and its value is greater than '1' we'll
                             * send DEvelopment Exception so the developer
                             * can, possibly, understand why this is happening
                             */
                            if( ( defined( 'DEVELOPMENT_MODE' ) && DEVELOPMENT_MODE >= 1 ) ) {
                                Handlers::development( $e, 503 );
                            } else {
                                Handlers::response( 503 );
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

                $this -> options -> dispatcher -> setDispatched( TRUE );

                /**
                 * @internal
                 * RouterException is thrown only by Application's Router
                 * when validating parameters against Request URI, by searching
                 * for missing required parameters (if any) or mal-formed
                 * parameters
                 */
                Handlers::production( $e );
            }
        }

        /**
         * @internal
         * No Controllers were able to handle the Request? So NOT FOUND
         *
         * Instead of send 404 header we'll display the Error Template File
         * only if we still allowed to do it
         */
        if( ! $this -> options -> dispatcher -> isDispatched() &&
            ! $this -> options -> dispatcher -> shouldReturn() ) {

            Handlers::response( 404 );
        }
    }
}