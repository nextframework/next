<?php

/**
 * Components Debug Error & Exception Handlers Class | Components\Debug\Handlers.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Debug\Exception;

use Next\Controller\Controller;                  # Controller Interface
use Next\Application\ApplicationException;       # Application Exception
use Next\HTTP\Response\ResponseException;        # Response Exception
use Next\HTTP\Headers\Fields\FieldsException;    # Header Field Exception
use Next\Controller\Router\NullRouter;           # Null Router Class
use Next\HTTP\Request;                           # HTTP Request
use Next\HTTP\Response;                          # HTTP Response
use Next\Debug\Exception\Handlers\Controllers\ErrorHandlerController;

/**
 * Registers Error and Exception handlers to deal with runtime errors
 * or uncaught Exceptions producing a nicer view
 *
 * @package    Next\Debug
 *
 * @uses       Next\Debug\Exception\Handler
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

                    // Adding Response Code

                    try {

                        $response -> addHeader( 500 );

                    } catch( FieldsException $e ) {}

                    /**
                     * Sending the Response
                     *
                     * @todo Test further if it's needed to handle sent
                     * headers like in previous version of Debug Module
                     */
                    $response -> send();

                } catch( ApplicationException $e ) {

                    // If fail here, you're in serious troubles XD

                    echo $e -> getMessage();
                }
            }
        });
    }
}