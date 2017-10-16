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

use Next\Controller\Controller;                  # Controller Interface
use Next\Controller\Router\NullRouter;           # Null Router Class
use Next\HTTP\Request;                           # HTTP Request
use Next\HTTP\Response;                          # HTTP Response
use Next\Exception\Handlers\Controllers\ErrorHandlerController;    # Error Handler Application Controller

/**
 * Registers Error and Exception handlers to deal with runtime errors
 * or uncaught Exceptions producing a nicer view
 *
 * @package    Next\Exception
 *
 * @uses       Next\Exception\Handler
 */
class ErrorHandler implements Handler {

    // Handler Interface Method Implementation

    /**
     * Registers the Error Handler
     */
    public static function register() {

        /**
         * @internal
         *
         * Starting with PHP 7 all Errors and Exceptions are thrown
         * through the new universal class \Error and because it's a
         * class its capture is done directly through set_exception_handler()
         * making this handler just a legacy for older versions
         */
        if( version_compare( PHP_VERSION, '7', '>=' ) ) return;

        /**
         * @internal
         *
         * Converting an intercepted error into an Exception,
         * similarly to what PHP 7 Error Class
         */
        set_error_handler( function( $severity, $message, $file, $line ) {
            throw new Exception( $message, Exception::PHP_ERROR, 500, NULL, $file, $line, $severity );
        });

        /**
         * Catching Fatal Errors and such
         */
        register_shutdown_function( function() {

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