<?php

namespace Next\Exception;

use Next\Application\ApplicationException;                      # Application Exception
use Next\HTTP\Response\ResponseException;                       # Response Exception
use Next\HTTP\Headers\Fields\FieldsException;                   # Header Field Exception
use Next\Controller\Router\NullRouter;                          # Null Router Class
use Next\HTTP\Request;                                          # HTTP Request
use Next\HTTP\Response;                                         # HTTP Response

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
     * Development Mode Exception Handler
     *
     * @param Exception $e
     *   Exception thrown
     */
    public static function development( \Exception $e ) {
        self::exception( $e, 'development' );
    }

    /**
     * Production Mode Exception Handler
     *
     * @param Exception $e
     *   Exception thrown
     */
    public static function production( \Exception $e ) {
        self::exception( $e, 'production' );
    }

    /**
     * Error Response
     *
     * @param integer $code
     *   Response Code
     */
    public static function errorResponse( $code ) {

        $code = (int) $code;

        try {

            $request = new Request;
            $request -> setQuery( array( 'code' => (int) $code ) );

            $response    = new Response;

            try {

                $application = new Handlers\HandlersApplication(

                    new NullRouter
                );

                $application -> setRequest( $request )
                             -> setResponse( $response );

                // Dispatching Controller

                call_user_func(

                    array( new ErrorController( $application ), 'main' )
                );

                try {

                    $response -> addHeader( $code ) -> send();

                } catch( FieldsException $e ) {

                    // Nothing to do!

                } catch( ResponseException $e ) {

                    /**
                     * If neither the Response nor the Internal Server Error,
                     * in case of an Exception, could be sent, let's abort
                     */
                    exit;
                }

            } catch( ApplicationException $e ) {

                echo $e -> getMessage();
            }

        } catch( \Exception $e ) {

            echo $e -> getMessage();
        }
    }

    // Auxiliary Methods

    /**
     * Exception Response Wrapper
     *
     * @param Exception $e
     *   Exception thrown
     *
     * @param string $mode
     *   Exception Mode, also an ExceptionController method to be invoked
     */
    private static function exception( \Exception $e, $mode ) {

        try {

            $request = new Request;

            $request -> setQuery( array( 'e' => $e ) );

            try {

                // Setting Up Application

                $application = new Handlers\HandlersApplication(

                    new NullRouter
                );

                $application -> setRequest( $request )
                             -> setResponse( new Response );

                // Dispatching Controller

                call_user_func(

                    array( new ExceptionController( $application ), $mode )
                );

                $response = $application -> getResponse();

                // Adding Response Code

                try {

                    /**
                     * @internal
                     * method_exists() is being used because, for example, an
                     * uncaught PDOException doesn't have this method because
                     * it doesn't extend our Exception Class
                     */
                    if( method_exists( $e, 'getResponseCode' ) ) {

                        $response -> addHeader( $e -> getResponseCode() );
                    }

                } catch( FieldsException $e ) {}

                // Sending the Response

                try {

                    $response -> send();

                } catch( ResponseException $e ) {

                    /**
                     * @internal
                     * If we can't send the Response, there is a
                     * Internal Server Error
                     */
                    self::error( 500 );
                }

            } catch( ApplicationException $e ) {

                echo $e -> getMessage();
            }

        } catch( \Next\Exception $e ) {

            // If fail here, you're in serious troubles XD

            echo $e -> getMessage();
        }
    }
}