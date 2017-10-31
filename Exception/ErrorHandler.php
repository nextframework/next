<?php

/**
 * Components Debug Error & Exception Handlers Class | Exception\ErrorHandler.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Exception;

use Next\Controller\Controller;    # Controller Interface
use Next\HTTP\Request;             # HTTP Request
use Next\HTTP\Response;            # HTTP Response

/**
 * Error Handler Application Controller
 */
use Next\Exception\Handlers\Controllers\ErrorHandlerController;

/**
 * Registers a Shutdown Function to deal with usually uncatchable runtime errors
 * resending them as Exceptions for nicer — and a bit more complete — view
 *
 * @package    Next\Exception
 *
 * @uses       Next\Controller\Controller
 *             Next\HTTP\Request
 *             Next\HTTP\Response
 *             Next\Exception\Handlers\Controllers\ErrorHandlerController
 *             Next\Exception\Handler
 */
class ErrorHandler implements Handler {

    // Handler Interface Method Implementation

    /**
     * Registers the Error Handler
     */
    public static function register() {

        /**
         * Catching Fatal Errors and such
         */
        register_shutdown_function( function() : void {

            if( ( $error = error_get_last() ) ) {

                if( ob_get_length() ) ob_clean();

                /**
                 * @internal
                 *
                 * Apparently throwing an Exception inside
                 * register_shutdown_function() does not trigger any
                 * Exception Handler, even if they have already been
                 * registered so, instead, we'll go full power and send
                 * a full HTTP Response, dispatching an Application Controller
                 */
                try {

                    $application = new Handlers\HandlersApplication;

                    $request  = $application -> getRequest();
                    $response = $application -> getResponse();

                    // Adding Exception as GET Parameters

                    $request -> setQuery(
                        [ 'e' => new ErrorException( $error['message'], $error['type'], $error['file'], $error['line'] ) ]
                    );

                    // Dispatching Controller

                    call_user_func( [ new ErrorHandlerController( $application ), 'error' ] );

                    $response -> addHeader( 500 ) -> send();

                } catch( Exception $e ) {

                    // If fail here, you're in serious troubles XD

                    echo $e -> getMessage();

                    if( defined( 'DEVELOPMENT_MODE' ) && DEVELOPMENT_MODE >= 1 ) {
                        echo ' - ', $e -> getFile(), '[', $e -> getLine(), ']';
                    }
                }
            }
        });
    }
}