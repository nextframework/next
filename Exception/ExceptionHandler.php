<?php

/**
 * Components Debug Error & Exception Handlers Class | Exception\ExceptionHandler.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Exception;

use Next\HTTP\Request;     # HTTP Request
use Next\HTTP\Response;    # HTTP Response

/**
 * Registers Error and Exception handlers to deal with runtime errors
 * or uncaught Exceptions producing a nicer view
 *
 * @package    Next\Exception
 *
 * @uses       Next\Exception\Handler
 */
class ExceptionHandler implements Handler {

    // Handler Interface Method Implementation

    /**
     * Registers the Exception Handler
     */
    public static function register() {

        set_exception_handler(

            ( defined( 'DEVELOPMENT_MODE' ) && DEVELOPMENT_MODE >= 1 ?

                [ __CLASS__, 'development' ] :

                [ __CLASS__, 'production' ]
            )
        );
    }

    // Exception Response-related Methods

    /**
     * Development Mode Exception Handler
     *
     * @param \ErrorException|\Exception|\Error|\Throwable|\Next\Exception $e
     *  Exception thrown
     *
     * @param integer|optional $code
     *  An alternative HTTP Response Code to be sent
     */
    public static function development( $e, $code = 500 ) {

        /**
         * @internal
         *
         * We - accidentally - got a low level error being handled here
         * instead of by the Error Handler or even by the
         * register_shutdown_function() when defining a type-hinting
         * for `$e` argument forcing it to be an instance of native
         * Exception Class
         *
         * For now, just in case something like it appears again,
         * "out of nowhere", let's create a valid Exception Object
         */
        if( ! $e instanceof \Exception && ! $e instanceof \Error ) $e = new Exception( (string) $e );

        if( method_exists( $e, 'getResponseCode' ) && (int) ( $c = $e -> getResponseCode() ) !== 0 ) {
            $code = $c;
        }

        self::handle(

            'Next\Exception\Handlers\Controllers\ExceptionHandlerController',

            'development', [ 'e' => $e ], $code
        );
    }

    /**
     * Production Mode Exception Handler
     *
     * @param \ErrorException|\Exception|\Error|\Throwable|\Next\Exception\Exception $e
     *  Exception thrown
     *
     * @param integer|optional $code
     *  An alternative HTTP Response Code to be sent
     */
    public static function production( $e, $code = 500 ) {

        /**
         * @see Handlers::development() for further explanations
         */
        if( ! $e instanceof \Exception && ! $e instanceof \Error ) $e = new Exception( (string) $e );

        if( method_exists( $e, 'getResponseCode' ) && (int) ( $c = $e -> getResponseCode() ) !== 0 ) {
            $code = $c;
        }

        self::handle(

            'Next\Exception\Handlers\Controllers\ExceptionHandlerController',

            'production', [ 'e' => $e ], $code
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

            'Next\Exception\Handlers\Controllers\ErrorHandlerController', 'status',

            [ 'code' => $code ], $code
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
    private static function handle( $controller, $action, array $query = [], $code = NULL ) {

        try {

            $application = new Handlers\HandlersApplication;

            $request  = $application -> getRequest();
            $response = $application -> getResponse();

            // Adding GET Parameters, if any

            if( count( $query ) > 0 ) {
                $request -> setQuery( $query );
            }

            // Dispatching Controller

            call_user_func( [ new $controller( $application ), $action ] );

            // Sending the Response

            if( $code !== NULL ) {
                $response -> addHeader( $code );
            }

            $response -> send();

        } catch( Exception $e ) {

            // If fail here, you're in serious troubles XD

            echo $e -> getMessage();

            if( defined( 'DEVELOPMENT_MODE' ) && DEVELOPMENT_MODE >= 1 ) {
                echo ' - ', $e -> getFile(), '[', $e -> getLine(), ']';
            }
        }
    }
}