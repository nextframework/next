<?php

namespace Next\Components\Debug;

use Next\Application\ApplicationException;       # Application Exception
use Next\HTTP\Response\ResponseException;        # Response Exception
use Next\HTTP\Headers\Fields\FieldsException;    # Header Field Exception
use Next\Controller\Router\NullRouter;           # Null Router Class
use Next\HTTP\Request;                           # HTTP Request
use Next\HTTP\Response;                          # HTTP Response

/**
 * Error & Exception Handlers Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Handlers {

    /**
     *  Handler Register
     *
     *  @param  string $what
     *    What Handler should be registered? Exception or Error
     */
    public static function register( $what ) {

        switch( $what ) {

            case 'error':

                set_error_handler( array( __CLASS__, 'error' ) );

                register_shutdown_function( function() {

                    $error = error_get_last();

                    if( ! is_null( $error ) ) {

                        if( ob_get_length() ) ob_clean();

                        self::error( $error['type'], $error['message'], $error['file'], $error['line'] );
                    }
                });

            break;

            case 'exception':

                set_exception_handler(

                    ( defined( 'DEVELOPMENT_MODE' ) && DEVELOPMENT_MODE >= 1 ?

                        array( __CLASS__, 'development' ) :

                        array( __CLASS__, 'production' )
                    )
                );

            break;
        }
    }

    // Error & Exception Response-related Methods

    /**
     * Development Mode Exception Handler
     *
     * @param Exception $e
     *  Exception thrown
     */
    public static function development( \Exception $e ) {

        // Enforcing Error Handler

        if( $e instanceof ErrorException ) {
            return self::error( 2, $e -> getMessage(), $e -> getFile(), $e -> getLine() );
        }

        $code = 500; // Default Code NOT ACCEPTABLE

        if( method_exists( $e, 'getResponseCode' ) ) {
            $code = $e -> getResponseCode();
        }

        self::handle(

            'Next\Components\Debug\Handlers\Controllers\ExceptionController',

            'development', array( 'e' => $e ), $code
        );
    }

    /**
     * Production Mode Exception Handler
     *
     * @param Exception $e
     *  Exception thrown
     */
    public static function production( \Exception $e ) {

        $code = 500; // Default Code NOT ACCEPTABLE

        if( method_exists( $e, 'getResponseCode' ) ) {
            $code = $e -> getResponseCode();
        }

        self::handle(

            'Next\Components\Debug\Handlers\Controllers\ExceptionController',

            'production', array( 'e' => $e ), $code
        );
    }

    /**
     * Error Response
     *
     * @param integer $code
     *  Response Code
     */
    public static function response( $code ) {

        self::handle(

            'Next\Components\Debug\Handlers\Controllers\ErrorController', 'status',

            array( 'code' => $code ), $code
        );
    }

    /**
     *  Error Exception
     *
     *  @param integer $severity
     *    Exception Severity
     *
     *  @param string $message
     *    Exception Message
     *
     *  @param string $file
     *    Filename were the error occurred
     *
     *  @param integer $line
     *    Line were the error occurred
     */
    public static function error( $severity, $message, $file, $line ) {

        self::handle(

            'Next\Components\Debug\Handlers\Controllers\ErrorController', 'error',

            array( 'e' => new ErrorException( $message, $severity, $file, $line ) ), 500
        );
    }

    // Auxiliary Methods

    /**
     * Exception Response Wrapper
     *
     * @param Exception $e
     *  Exception thrown
     *
     * @param string $mode
     *  Exception Mode, also an ExceptionController method to be invoked
     */
    private static function handle( $controller, $action, array $query = array(), $code = NULL ) {

        try {

            $request  = new Request;
            $response = new Response;

            if( count( $query ) > 0 ) {
                $request -> setQuery( $query );
            }

            try {

                // Setting Up Application

                $application = new Handlers\HandlersApplication;

                $application ->  setRequest( $request )
                             -> setResponse( $response );

                // Dispatching Controller

                call_user_func(

                    array( new $controller( $application ), $action )
                );

                $response = $application -> getResponse();

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
                     * If we can't send the Response, there is a
                     * Internal Server Error, but it'll only be sent if we're still
                     * able to send headers, which is not the case of very specific scenarios,
                     * when the current buffer length cannot determined and thus, cleansed.
                     * Otherwise, this would cause an infinite loop
                     */
                    if( Response::canSendHeaders() ) self::response( 500 );
                }

            } catch( ApplicationException $e ) {

                echo $e -> getMessage();
            }

        } catch( ResponseException $e ) {

            /**
             * This ResponseException is the result of a ResponseException being caught,
             * triggering Handlers::error() to send an Internal Server Error status code
             * when any header has already been sent but the output buffer could not be
             * cleansed during Handler registration because its length could not be retrieved
             *
             * And by silencing this here we avoid the echo below to be called
             */

        } catch( Exception $e ) {

            // If fail here, you're in serious troubles XD

            echo $e -> getMessage();
        }
    }
}