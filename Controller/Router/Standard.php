<?php

/**
 * Standard Controller Router Class | Controller\Router\Standard.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\Controller\Router;

use Next\Components\Object;              # Object Class
use Next\Components\Utils\ArrayUtils;    # Array Utils Class
use Next\Application\Application;        # Application Interface
use Next\HTTP\Request;                   # Request Class

/**
 * Standard Controller Router based on PHP Arrays
 *
 * @package    Next\Controller\Router
 */
class Standard extends AbstractRouter {

    /**
     * Default Options
     *
     * @var array $defaultOptions
     */
    protected $defaultOptions = array(
        'filePath' => 'data/routes.php'
    );

    // RegExp and String Delimiter Constants

    /**
     * Arguments Separator Token
     *
     * @var string
     */
    const SEPARATOR_TOKEN    = '|';

    /**
     * Finds a Route that matches to an Application AND current Request
     *
     * @param \Next\Application\Application $application
     *  Application to iterate Controllers
     *
     * @return array|object|boolean
     *
     *  If a Route could be match against current Request URI an
     *  array or an object will be returned (depending on Connection
     *  Driver configuration).
     *
     *  If none could, FALSE will be returned
     */
    public function find( Application $application ) {

        // Shortening Declarations

        $request = $application -> getRequest();

        $URI = $request -> getURL( FALSE );

        // Removing trailing slash if on slash (/) route

        if( $request -> getRequestURI() == '/' ) {
            $URI = rtrim( $URI, '/' );
        }

        // Including PHP file

        include $this -> options -> filePath;

        $data = ( isset( $routes ) ? $routes : NULL );

        if( is_array( $data ) ) {

            // Parsing content as a PHP array

            $data = array_filter(

                $data,

                function( $route ) use( $application, $request, $URI ) {

                    /**
                     * To be a matching route it must:
                     *
                     * - Belong to the same Application Controller (class name)
                     * - Correspond to the same Request Method of the current Request
                     * - Have its route RegExp match the Request URI of current Request
                     */
                    return ( $route['application']   == $application -> getClass() -> getName() &&
                           ( $route['requestMethod'] ==          $request -> getRequestMethod() &&
                             preg_match( sprintf( '@^%s$@i', $route['URI'] ), $URI ) != 0 ) );
                }
            );

            // No matches found!

            if( count( $data ) == 0 ) return FALSE;

            // Match found, let's prepare everything for a successful Dispatch

            /**
             * @internal
             * $data is being rewritten to point to the first in the filtered dataset
             * because this contains the most specific route, counter-balancing
             * the lack of gluttony of preg_match() that doesn't match as much as possible
             */
            $data = Object::map( array_shift( $data ) );

            /**
             * @internal
             * Setting Up Found Controller and its action to be used in View,
             * as part of findFilebySpec() method
             */
            $this -> controller = $data -> controller;

            $this -> method     = $data -> method;

            // Analyzing Params

            $requiredParams = unserialize( $data -> requiredParams );

            // Lookup for Required Params in URL

            if( count( $requiredParams ) > 0 ) {

                $this -> lookup( $requiredParams, $URI, $request -> getQuery() );
            }

            /**
             * @internal
             * Validating Required Params
             * Only Parameters with a List|of|Acceptable|Values or with a defined REGEX
             * will be validated
             */
            $this -> validate(

                array_filter(

                    $requiredParams,

                    function( $current ) {

                        return ( ! empty( $current['acceptable'] ) || ! empty( $current['regex'] ) );
                    }
                ),

                $URI
            );

            // Process Dynamic Params, in order to register them as Request Params

            $params = $this -> process(

                array_merge( $requiredParams, unserialize( $data -> optionalParams ) ),

                $URI
            );

            // Merging manually defined GET query

            $data -> params = array_merge( $params, $request -> getQuery() );

            // Discarding Unnecessary Information

            unset( $data -> requiredParams, $data -> optionalParams );

            return $data;
        }

        return FALSE;
    }

    // Auxiliary methods

    /**
     * Creates a \Next\HTTP\Stream\Reader object to read data from input
     * PHP array generated by one of the \Next\Tools\Routes\Generators\Generator
     *
     * @throws \Next\Controller\Router\RouterException
     *  Filepath to the PHP array was not informed or it's empty
     */
    protected function connect() {

        // Checking if PHP File Exists

        if( $this -> options -> filePath === FALSE || ! file_exists( $this -> options -> filePath ) ) {

            throw RouterException::connectionFailure(

                'PHP Database File <strong>%s</strong> doesn\'t exist',

                RouterException::CONNECTION_FAILED,

                array( $this -> options -> filePath )
            );
        }
    }

    // Abstract Methods Implementation

    /**
     * Lookup for Required Params in URL
     *
     * @param array $params
     *  Array of Params to validate
     *
     * @param string $URI
     *  Request URI to be checked against
     *
     * @param array|optional $queryData
     *  Manually set GET parameters to be considered as validatable arguments too
     *
     * @throws \Next\Controller\Router\RouterException
     *  Any of the Required Parameters is missing or has no value
     */
    protected function lookup( array $params, $URI, array $queryData = array() ) {

        /**
         * Merging manually defined GET query so they can
         * be considered as validatable arguments too
         */
        if( count( $queryData ) != 0 ) {

            foreach( $queryData as $key => $value ) {

                $params[] = array( 'name' => $key );
            }
        }

        array_walk(

            $params,

            function( $current ) use( $URI ) {

                preg_match( sprintf( '/\/%s\/.+\/?/', $current['name'] ), $URI, $matches );

                // Required argument is not present in URL and neither in GET superglobal?

                if( count( $matches ) == 0 && ! isset( $_GET[ $current['name'] ] ) ) {

                    // Fine! Let's also send a 400 Response Header (Bad Request)

                    throw RouterException::missingParameter( $current['name'] );
                }
            }
        );
    }

    /**
     * Check if required parameters are valid in a list of valid values
     *
     * @param array $params
     *  Route Params
     *
     * @param string $URI
     *  Route URI
     *
     * @throws \Next\Controller\Router\RouterException
     *  Any of the Required Parameters has an invalid value from a
     *   [list|of|possible|values]
     */
    protected function validate( array $params, $URI ) {

        // Do we have something to work with?

        if( count( $params ) == 0 ) {
            return;
        }

        $token = self::SEPARATOR_TOKEN;

        array_walk(

            $params,

            function( $current ) use( $URI, $token ) {

                // Finding argument value

                preg_match(

                    sprintf( '/\/%s\/([^\/]+)\/?/', $current['name'] ), $URI, $value
                );

                // Validating parameter value with a predefined REGEXP

                if( ! empty( $current['regex'] ) ) {

                    /**
                     * If argument value does not match defined REGEX it is
                     * automatically blocked regardless if a list given value is
                     * valid against it (even if this may sound ridiculous)
                     */
                    if( preg_match( sprintf( '/%s/', $current['regex'] ), $value[ 1 ] ) == 0 ) {
                        throw RouterException::invalidParameter( $current );
                    }

                } else {

                    $valid = explode( $token, $current['acceptable'] );

                    // If Parameter is not in the list...

                    if( ! in_array( $value[ 1 ], $valid ) ) {

                        // ... and there is no Default Value defined for it

                        if( ! empty( $current['default'] ) ) {

                            // Teh, he, he ^^

                            throw RouterException::invalidParameter( $current );
                        }
                    }
                }
            }
        );
    }

    /**
     * Process Dynamic Params, in order to register them as Request Params
     *
     * @param array $params
     *  Array of Params to parse
     *
     * @param string $URI
     *  Request URI to be checked against
     *
     * @return array
     *  An array with all parsed Dynamic Parameters and their proper values
     */
    protected function process( array $params, $URI ) {

        // Do we have something to work with?

        if( count( $params ) == 0 ) {
            return array();
        }

        $pairs = array();

        foreach( $params as $param ) {

            $temp = $param['name'];

            // Listing Parameters Values

            preg_match( sprintf( '/\/%s\/([^\/]+)\/?/', $temp ), $URI, $value );

            // Finding Parameter Value(s)

                // Match! Which means current Parameter (in iteration) is present in Requested URI

            if( count( $value ) > 0 ) {

                $pairs[ $temp ] = $this -> findParameterValue( $param, $value[ 1 ] );

            } else {

                /**
                 * @internal
                 * No match!
                 *
                 * Which means current Parameter (in iteration) is not present in Requested URI
                 *
                 * - OR -
                 *
                 * Parameter is mal-formed. E.g (of two params): /param1/value1/param2
                 *
                 * As optional Parameter, otherwise the flow would be stopped
                 * by \Next\Controller\Router\Standard::lookup(), we'll use the
                 * Default value, if defined in Route, or NULL, if not
                 */
                $pairs[ $temp ] = ( ! empty( $param['default'] ) ? $param['default'] : NULL );
            }
        }

        return $pairs;
    }

    // Auxiliary Methods

    /**
     * Find Parameter Value
     *
     * @param array $param
     *  Parameter Data
     *
     * @param string $value
     *  Parameter Value
     *
     * @return string
     *
     *   - Input Parameter Value, if parameter is present and is valid
     *
     *   - The Default Parameter Value if parameter is missing or is invalid AND
     *     a Default Value is defined in Router
     *
     *   - NULL, in any other cases
     */
    private function findParameterValue( $param, $value ) {

        // Validating parameter value with a predefined REGEXP

        if( ! empty( $param['regex'] ) ) {

            // If argument value matches defined REGEX is automatically accepted "as is"

            if( preg_match( sprintf( '/%s/', $param['regex'] ), $value ) != 0 ) {
                return $value;
            }

        } else {

            // Comparing value against a List, if any

            if( ! empty( $param['acceptable'] ) ) {

                $list = explode( self::SEPARATOR_TOKEN, $param['acceptable'] );

                // If given value is in the list, let's accept it

                if( in_array( $value, $list ) ) {

                    return $value;

                } else {

                    // Otherwise let's try to find a default value for it

                    return ( ! empty( $param['default'] ) ? $param['default'] : NULL );
                }

            } else {

                // We don't have a List, so let's return input value "as is"

                return $value;
            }
        }
    }
}