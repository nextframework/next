<?php

namespace Next\Controller;

use Next\Controller\Dispatcher\DispatcherException;    # Dispatcher Exception Class
use Next\Components\Object;                            # Object Class
use Next\Application\Chain as Applications;            # Applications Chain
use Next\Controller\Dispatcher\Dispatcher;             # Controllers Dispatcher Interface
use Next\Controller\Dispatcher\Standard;               # Standard Dispatcher Class
use Next\HTTP\Headers\Fields\FieldsException;          # Header Fields Exception Class
use Next\HTTP\Request;                                 # Request Class
use Next\HTTP\Response;                                # Response Class
use Next\HTTP\Response\ResponseException;              # Response Exception Class
use Next\Components\Debug\Handlers;                    # Errors & Exceptions Handlers

/**
 * Front Controller Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Front extends Object {

    /**
     * Controller Dispatcher Object
     *
     * @var Next\Controller\Dispatcher\Dispatcher $dispatcher
     */
    private $dispatcher;

    /**
     * Applications Chain
     *
     * @var Next\Application\Chain $applications
     */
    private $applications;

    /**
     * Front Controller Constructor
     *
     * @param Next\Application\Chain $applications
     *   Applications Chain
     */
    public function __construct( Applications $applications ) {

        $this -> applications = $applications;

        // Setting Dispatcher Object

        $this -> dispatcher = new Standard;
    }

    /**
     * Dispatches a Controller
     *
     * The centered process includes:
     *
     * <ul>
     *
     *     <li>Iterates through available applications</li>
     *     <li>Configures its Resources</li>
     *     <li>Tries to match a Route against Requested URI</li>
     *     <li>Instantiates the proper Controller</li>
     *     <li>Sends the Response to browser</li>
     *
     * </ul>
     *
     * @return mixed|void
     *   Returns what the chosen Dispatcher have dispatched, if configured to
     *   do so
     */
    public function dispatch() {

        // Iterating through Application's Collection...

        foreach( $this -> applications as $application ) {

            // ... and trying to match a Route

            try {

                $match = $application -> getRouter() -> find( $application );

                // We have a match!

                if( $match !== FALSE ) {

                    // Shortening Application's Response

                    $response = $application -> getResponse();

                    // Adding some Headers before Send

                    try {

                        $response -> addHeader(

                            new \Next\HTTP\Headers\Fields\Response\XPoweredBy(

                                'Next Framework'
                            )
                        );

                    } catch( FieldsException $e ) {}

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

                        } catch( DispatcherException $e ) {

                            /**
                             *  Since DispatcherException is thrown only if a
                             *  ReflectionException is caught and catching this
                             *  Exception is a mere formality, we can use
                             *  HTTP Code 503 (Service Unavailable) safely
                             */
                            Handlers::response( 503 );
                        }

                        // So far, so good. Let's try to send the Response

                            // Should we return the Response?

                        if( $response -> shouldReturn() ) {
                            return $response;
                        }

                        $response -> send();
                    }
                }

            } catch( \Next\Controller\Router\RouterException $e ) {

                $this -> dispatcher -> setDispatched( TRUE );

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
        if( ! $this -> dispatcher -> isDispatched() &&
            ! $this -> dispatcher -> shouldReturn() ) {

            Handlers::response( 404 );
        }
    }

    /**
     * Set a Dispatcher Object
     *
     * @param Next\Controller\Dispatcher\Dispatcher $dispatcher
     *   Dispatcher Object
     *
     * @return Next\Controller\Front
     *   Front Controller Instance (Fluent Interface)
     */
    public function setDispatcher( Dispatcher $dispatcher ) {

        $this -> dispatcher =& $dispatcher;

        return $this;
    }

    /**
     * Get Dispatcher Object
     *
     * @return Next\Controller\Dispatcher\Dispatcher
     *   Dispatcher Object
     */
    public function getDispatcher() {
        return $this -> dispatcher;
    }
}