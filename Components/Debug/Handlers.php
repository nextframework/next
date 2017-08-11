<?php

/**
 * Components Debug Error & Exception Handlers Class | Components\Debug\Handlers.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Components\Debug;

use Next\Controller\Controller;                  # Controller Interface
use Next\Application\ApplicationException;       # Application Exception
use Next\HTTP\Response\ResponseException;        # Response Exception
use Next\HTTP\Headers\Fields\FieldsException;    # Header Field Exception
use Next\Controller\Router\NullRouter;           # Null Router Class
use Next\HTTP\Request;                           # HTTP Request
use Next\HTTP\Response;                          # HTTP Response

/**
 * Registers Error and Exception handlers to deal with runtime errors
 * or uncaught Exceptions producing a nicer view
 *
 * @package    Next\Components\Debug
 */
class Handlers {

    /**
     * Handler Register
     */
    public static function register() {

        /**
         * @internal
         *
         * Starting with PHP 7 all Errors and Exceptions are thrown
         * through the new universal class \Error and because it's a
         * class capture it is done directly with set_exception_handler()
         * making this handler just a legacy for older versions
         */
        if( version_compare( PHP_VERSION, '7' ) < 0 ) {

            /**
             * @see Handlers::error()
             */
            set_error_handler( array( __CLASS__, 'error' ) );

            register_shutdown_function( function() {

                $error = error_get_last();

                if( ! is_null( $error ) ) {

                    if( ob_get_length() ) ob_clean();

                    self::error( $error['type'], $error['message'], $error['file'], $error['line'] );
                }
            });
        }

        set_exception_handler(

            ( defined( 'DEVELOPMENT_MODE' ) && DEVELOPMENT_MODE >= 1 ?

                array( __CLASS__, 'development' ) :

                array( __CLASS__, 'production' )
            )
        );
    }

    // Error & Exception Response-related Methods

    /**
     * Development Mode Exception Handler
     *
     * @param \ErrorException|\Exception|\Error|\Throwable|\Next\Components\Debug\Exception $e
     *  Exception thrown
     *
     * @param integer|optional $code
     *  An alternative HTTP Response Code to be sent
     */
    public static function development( $e, $code = 500 ) {

        /**
         * @internal
         *
         * With PHP 7 we have the Throwable interface implemented by
         * all Exception classes as well by the new universal
         * error/exception class \Error
         *
         * Use\Throwable as type-hinting would be better but
         * would also prevent older PHP versions to use the framework,
         * so we condition it here, as some form of legacy
         */
        if( $e instanceof \Exception || ( version_compare( PHP_VERSION, '7' ) >= 0 && $e instanceof \Throwable ) ) {

            $e = new Exception( $e -> getMessage(), Exception::PHP_ERROR );

        } elseif( is_string( $e ) ) {

            /**
             * @internal
             *
             * Just in case if something comes out of nowhere
             * and is caught by register_shutdown_functions()
             *
             * @var Exception
             */
            $e = new Exception( $e, Exception::UNKNOWN );
        }

        if( method_exists( $e, 'getResponseCode' ) && (int) ( $c = $e -> getResponseCode() ) !== 0 ) {
            $code = $c;
        }

        self::handle(

            'Next\Components\Debug\Handlers\Controllers\ExceptionController',

            'development', array( 'e' => $e ), $code
        );
    }

    /**
     * Production Mode Exception Handler
     *
     * @param \ErrorException|\Exception|\Error|\Throwable|\Next\Components\Debug\Exception $e
     *  Exception thrown
     *
     * @param integer|optional $code
     *  An alternative HTTP Response Code to be sent
     */
    public static function production( $e, $code = 500 ) {

        /**
         * @see Handlers::development() for further explanations
         */
        if( $e instanceof \Exception || ( version_compare( PHP_VERSION, '7' ) >= 0 && $e instanceof \Throwable ) ) {

            $e = new Exception( $e -> getMessage(), Exception::PHP_ERROR );

        } elseif( is_string( $e ) ) {

            $e = new Exception( $e, Exception::UNKNOWN );
        }

        if( method_exists( $e, 'getResponseCode' ) && (int) ( $c = $e -> getResponseCode() ) !== 0 ) {
            $code = $c;
        }

        self::handle(

            'Next\Components\Debug\Handlers\Controllers\ExceptionController',

            'production', array( 'e' => $e ), $code
        );
    }

    /**
     * Wrapper method for Error Responses in which just the
     * HTTP Response Code is passed and the message comes
     * from a predefined list
     *
     * @param integer $code
     *  Response Code
     *
     * @see Next\HTTP\Response
     */
    public static function response( $code ) {

        self::handle(

            'Next\Components\Debug\Handlers\Controllers\ErrorController', 'status',

            array( 'code' => $code ), $code
        );
    }

    /**
     * Error Handler Wrapper
     *
     * @param integer $severity
     *  Exception Severity
     *
     * @param string $message
     *  Exception Message
     *
     * @param string $file
     *  Filename were the error occurred
     *
     * @param integer $line
     *  Line were the error occurred
     */
    public static function error( $severity, $message, $file, $line ) {

        self::handle(

            'Next\Components\Debug\Handlers\Controllers\ErrorController', 'error',

            array( 'e' => new ErrorException( $message, $severity, $file, $line ) ), 500
        );
    }

    // Auxiliary Methods

    /**
     * Exception Handler Wrapper
     *
     * @param \Next\Controller\Controller|string $controller
     *  Controller to be dispatched
     *
     * @param string $action
     *  Action Method to be called in that Controller
     *
     * @param array|optional $query
     *  A list of arguments to be passed to that Controller as GET parameters
     *
     * @param integer|mixed|optional
     *  An HTTP Status Code to be sent as Response Header
     */
    private static function handle( $controller, $action, array $query = array(), $code = NULL ) {

        try {

            $application = new Handlers\HandlersApplication;

            $request  = $application -> getRequest();
            $response = $application -> getResponse();

            // Adding GET Parameters, if any

            if( count( $query ) > 0 ) {
                $request -> setQuery( $query );
            }

            // Dispatching Controller

            call_user_func( array( new $controller( $application ), $action ) );

            // Adding Response Code

            if( ! is_null( $code ) ) {

                try {

                    $response -> addHeader( $code );

                } catch( FieldsException $e ) {}
            }

            // Sending the Response

            try {

                $response -> send();

            } catch( ResponseException $e ) {

                /**
                 * @internal
                 *
                 * If we can't send the Response, there is a
                 * Internal Server Error, but it'll only be sent if
                 * we're still able to send headers, which is not the
                 * case of very specific scenarios, when the current
                 * buffer length cannot determined and thus, cleansed.
                 *
                 * Otherwise, this would cause an infinite loop
                 */
                if( Response::canSendHeaders() ) self::response( 500 );
            }

        } catch( ApplicationException $e ) {

            // If fail here, you're in serious troubles XD

            echo $e -> getMessage();
        }
    }
}