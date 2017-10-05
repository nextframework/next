<?php

/**
 * Standard Controller Router Class | Controller\Router\Standard.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Controller\Router;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Interfaces\Verifiable;    # Verifiable Interface
use Next\Application\Application;             # Application Interface

use Next\Components\Object;                   # Object Class
use Next\HTTP\Request;                        # Request Class

/**
 * Standard Controller Router based on PHP Arrays
 *
 * @package    Next\Controller\Router
 */
class Standard extends AbstractRouter implements Verifiable {

    // RegExp and String Delimiter Constants

    /**
     * Arguments Separator Token
     *
     * @var string
     */
    const SEPARATOR_TOKEN    = '|';

    // Router Interface Method Implementation

    /**
     * Finds a matching Route for the Application -AND- current Request URI
     *
     * @return array|object|boolean
     *  If a Route could be match against current Request URI an
     *  array or an object will be returned (depending on Connection
     *  Driver configuration).
     *
     *  If none could, FALSE will be returned
     */
    public function find() {

        // Shortening Declarations

        $request = $this -> options -> application -> getRequest();

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

                function( $route ) use( $request, $URI ) {

                    /**
                     * @internal
                     *
                     * To be a matching route it must:
                     *
                     * - Belong to the same Application Controller (classname)
                     * - Correspond to the same Request Method of
                     *   the current Request
                     * - Have its route RegExp matching the Request URI
                     *   of current Request
                     */
                    return ( $route['application']   == $this -> options -> application -> getClass() -> getName() &&
                           ( $route['requestMethod'] ==          $request -> getRequestMethod() &&
                             preg_match( sprintf( '@^%s$@i', $route['URI'] ), $URI ) != 0 ) );
                }
            );

            // No matches found!

            if( count( $data ) == 0 ) return FALSE;

            /**
             * @internal
             *
             * $data is being rewritten to point to the first in the
             * filtered dataset because this contains the most specific
             * Route, counter-balancing the lack of gluttony
             * of preg_match() that doesn't match as much as possible
             */
            $data = Object::map( array_shift( $data ) );

            /**
             * @internal
             *
             * Setting Up Controller and its Action found to be used
             * later, maybe by View Engines as part of FileSpec detection
             */
            $this -> controller = $data -> controller;
            $this -> method     = $data -> method;

            // Analyzing Params

            $requiredParams = unserialize( $data -> requiredParams );

            if( count( $requiredParams ) > 0 ) {

                /**
                 * @internal
                 *
                 * Merging manually defined GET query (i.e. `?param=value` )
                 * so they can be considered as validatable arguments too
                 */
                foreach( $request -> getQuery() as $key => $value ) {
                    $requiredParams[] = [ 'name' => $key ];
                }

                $this -> lookup( $requiredParams, $URI );
            }

            /**
             * @internal
             *
             * Validating Required Params against a List|of|Acceptable|Values
             * or with a defined REGEX, if defined
             */
            $this -> validate(

                array_filter(

                    $requiredParams,

                    function( $current ) {

                        return ( ! empty( $current['acceptable'] ) ||
                                    ! empty( $current['regex'] ) );
                    }
                ),

                $URI
            );

            // Processing Dynamic Params, in order to register them as Request Params

            $params = $this -> process(

                array_merge( $requiredParams, unserialize( $data -> optionalParams ) ),

                $URI
            );

            // Merging manually defined GET query

            $data -> params = array_merge( $params, $request -> getQuery() );

            return $data;
        }

        return FALSE;
    }

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity
     *
     * @throws \Next\Controller\Router\RouterException
     *  Filepath to the PHP array was not informed or it's empty
     */
    public function verify() {

        // Checking if PHP File Exists

        if( $this -> options -> filePath === FALSE ||
              ! file_exists( $this -> options -> filePath ) ) {

            throw new InvalidArgumentException(

                sprintf(

                    'PHP Database File <strong>%s</strong> doesn\'t exist',

                    $this -> options -> filePath
                )
            );
        }
    }

    // Parameterizable Interface Method Overwriting

    /**
     * Set Class Options.
     * Defines a default filepath for PHP-array with Generated Routes
     */
    public function setOptions() {
        return [ 'filePath' => __DIR__ . '/routes.php' ];
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
     * @throws \Next\Controller\Router\RouterException
     *  Any of the Required Parameters is missing or has no value
     */
    protected function lookup( array $params, $URI ) {

        array_walk(

            $params,

            function( $current ) use( $URI ) {

                preg_match( sprintf( '/\/%s(?:\/|\?).+\/?/', $current['name'] ), $URI, $matches );

                /**
                 * @internal
                 *
                 * Required argument is not present in URL and neither
                 * in GET superglobal, so let's also deny access
                 * sending a 400 Response Header (Bad Request)
                 */
                if( count( $matches ) == 0 && ! isset( $_GET[ $current['name'] ] ) ) {

                    throw new InvalidArgumentException(

                        sprintf(

                            'Missing or malformed Required Parameter <strong>%s</strong>',

                            $current['name']
                        ),

                        NULL, 400
                    );
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

        // Nothing to validate

        if( count( $params ) == 0 ) return;

        array_walk(

            $params,

            function( $current ) use( $URI ) {

                // Finding argument value

                preg_match(
                    sprintf( '/\/%s(?:\/|\?)([^\/]+)\/?/', $current['name'] ), $URI, $value
                );

                // No values to check

                if( count( $value ) == 0 ) return TRUE;

                // Validating parameter value against a predefined REGEXP

                if( ! empty( $current['regex'] ) ) {

                    /**
                     * @internal
                     *
                     * If argument value does not match predefined REGEX
                     * it is automatically blocked regardless if a
                     * list|of|valid|values has been provided and the
                     * argument is valid — even if this may sound ridiculous
                     */
                    if( preg_match( sprintf( '/%s/', $current['regex'] ), $value[ 1 ] ) == 0 ) {

                        throw new InvalidArgumentException(

                            sprintf(

                                'Invalid Required Parameter <strong>%s</strong>',

                                $current['name']
                            ),

                            NULL, 400
                        );
                    }

                } else {

                    $valid = explode( self::SEPARATOR_TOKEN, $current['acceptable'] );

                    /**
                     * @internal
                     *
                     * There's not a RegExp to validate, then
                     * let's check against a list|of|valid|values
                     */
                    if( ! in_array( $value[ 1 ], $valid ) ) {

                        /**
                         * @internal
                         *
                         * A Route Argument might be defined as required
                         * but it doesn't need to be defined by the
                         * User so let's check if a default Value has
                         * been provided as fallback
                         */
                        if( ! empty( $current['default'] ) ) {

                            throw new InvalidArgumentException(

                                sprintf(

                                    'Invalid Required Parameter <strong>%s</strong>',

                                    $current['name']
                                ),

                                NULL, 400
                            );
                        }
                    }
                }
            }
        );
    }

    /**
     * Process Dynamic Params, in order to register them as HTTP GET Params
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

        // Nothing to process

        if( count( $params ) == 0 ) return [];

        $pairs = [];

        foreach( $params as $param ) {

            $temp = $param['name'];

            // Listing Parameters Values

            preg_match(
                sprintf( '/\/%s\/([^\/]+)\/?/', $temp ), $URI, $value
            );

            // Finding Parameter Value(s)

            /**
             * @internal
             *
             * If we got a match means current Parameter in iteration
             * is present in Requested URI and we can get its value
             *
             * @see Standard::findParameterValue()
             */
            if( count( $value ) > 0 ) {

                $pairs[ $temp ] = $this -> findParameterValue( $param, $value[ 1 ] );

            } else {

                /**
                 * @internal
                 *
                 * If we don't have a match, the Route Argument *may*
                 * not present on Request URI, but the Request URI
                 * itself could be malformed. — i.e. /param1/value1/param2
                 *
                 * Being an optional Parameter — otherwise the flow
                 * would've been interrupted by Standard::lookup(),
                 * we'll use the Default Value, if defined in Route,
                 * or `NULL`, if not
                 */
                $pairs[ $temp ] = ( ! empty( $param['default'] ) ? $param['default'] : NULL );
            }
        }

        return $pairs;
    }

    // Auxiliary Methods

    /**
     * Finds Parameter Value
     *
     * @param array $param
     *  Parameter Data
     *
     * @param string $value
     *  Parameter Value
     *
     * @return string
     *  - Input Parameter Value, if parameter is present and is valid
     *  - Default Parameter Value, if parameter is missing or is
     *    invalid -AND- a Default Value is defined in Router
     *  - `NULL`, in any other cases
     */
    private function findParameterValue( $param, $value ) {

        /**
         * @internal
         *
         * If we have a RegExp to work with and it matches the
         * argument value, it's accepted "as is"
         */
        if( ! empty( $param['regex'] ) &&
                preg_match( sprintf( '/%s/', $param['regex'] ), $value ) != 0 ) {

            return $value;
        }

        /**
         * @internal
         *
         * Otherwise let's compare against a list|of|valid|values if defined
         */
        if( ! empty( $param['acceptable'] ) ) {

            $list = explode( self::SEPARATOR_TOKEN, $param['acceptable'] );

            /**
             * @internal
             *
             * If the argument value is on the list, we accept it, otherwise
             * we use its Default Value.
             * If no Default Value was defined, we set it as `NULL`
             */

            if( in_array( $value, $list ) ) return $value;

            return ( ! empty( $param['default'] ) ? $param['default'] : NULL );
        }

        // Not RegExp and not list, let's accept it "as is"

        return $value;
    }
}