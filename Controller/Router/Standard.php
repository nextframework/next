<?php

namespace Next\Controller\Router;

use Next\Components\Utils\ArrayUtils;      # Array Utils Class
use Next\Application\Application;          # Application Interface
use Next\HTTP\Request;                     # Request Class

/**
 * Standard Controller Router Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Standard extends AbstractRouter {

    /**
     * Default SQLite DB Path
     *
     * @var string
     */
    const DBPATH    = 'Data/Routes.sqlite';

    // RegExp and String Delimiter Constants

    /**
     * Arguments Separator Token
     *
     * @var string
     */
    const SEPARATOR_TOKEN       = '|';

    /**
     * SQLite Connection Resource
     *
     * @var mixed $dbh
     */
    protected $dbh;

    /**
     * Additional Initialization
     *
     * Extends functionality of Standard Router SQLite Handler
     */
    protected function init() {

        // Extending SQLITE Custom Resources

        $this -> extend();
    }

    /**
     * Finds a Route that matches to an Application AND current Request
     *
     * @param Next\Application\Application $application
     *   Application to iterate Controllers
     *
     * @return array|object|boolean
     *
     *   If a Route could be match against current Request URI an
     *   array or an object will be returned (depending on Connection
     *   Driver configuration).
     *
     *   If none could, FALSE will be returned
     */
    public function find( Application $application ) {

        // Shortening Declarations

        $request = $application -> getRequest();

        $URI     = $request -> getRequestUri();

        // Searching the Request in Routes Database

        $stmt = $this -> dbh -> prepare( 'SELECT `requestMethod`, `class`, `method`,
                                                 `requiredParams`, `optionalParams`
                                            FROM `routes`
                                                WHERE `application` = ? AND
                                                    ( `requestMethod` = ? AND ? REGEXP `URI` ) AND INSTR( `URI`, ? )' );

        $stmt -> execute(

            array(

                $application -> getClass() -> getName(),

                $request -> getRequestMethod(),

                $URI, $URI
            )
        );

        $resultset = $stmt -> fetch();

        // Match found, let's prepare everything for a successful Dispatch

        if( $resultset !== FALSE ) {

            /**
             * @internal
             * Setting Up Found Controller and its action to be used in View,
             * as part of findFilebySpec() method
             */
            $this -> controller =& $data -> class;

            $this -> action     =& $data -> method;

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
     * Establishes a Connection with the SQLITE Database File
     *
     * @throws Next\Controller\Router\RouterException
     *   SQLITE Database File doesn't exist in defined directory
     */
    protected function connect() {

        // Checking if Database File Exists

        if( ! file_exists( $this -> options -> dbPath ) ) {

            throw RouterException::connectionFailure(

                'Routes Database File %s doesn\'t exist in Data Directory',

                array( $this -> options -> dbPath )
            );
        }

        $adapter = new \Next\DB\Driver\PDO\Adapter\SQLite(

                       array(

                           'dbPath' => $this -> options -> dbPath
                       )
                   );

        $this -> dbh = $adapter -> getConnection();
    }

    // Parameterizable Interface Methods Implementation

    /**
     * Set Standard Router Options
     *
     * @return array
     *
     *  <p>An associative array with Standard Router default options</p>
     *
     *  <p>
     *
     *      <ul>
     *
     *          <li>
     *
     *              <p><strong>dbPath</strong></p>
     *
     *              <p>Path for SQLite Database File</p>
     *
     *              <p>Default Value: <strong>Data/Routes.sqlite</strong></p>
     *
     *          </li>
     *
     *      </ul>
     *
     *  </p>
     */
    public function setOptions() {

        return array(

            'dbPath' => self::DBPATH
        );
    }

    // Abstract Methods Implementation

    /**
     * Lookup for Required Params in URL
     *
     * @param array $params
     *   Array of Params to validate
     *
     * @param string $URI
     *   Request URI to be checked against
     *
     * @param array|optional $queryData
     *   Manually set GET parameters to be considered as validatable arguments too
     *
     * @throws Next\Controller\Router\RouterException
     *   Any of the Required Parameters is missing or has no value
     */
    protected function lookup( array $params, $URI, array $queryData = array() ) {

        /**
         * Merging manually defined GET query so they can
         * be considered as validatable arguments too
         */
        if( count( $queryData ) != 0 ) {
            $params = array_merge( $params, array_keys( $queryData ) );
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
     *   Route Params
     *
     * @param string $URI
     *   Route URI
     *
     * @throws Next\Controller\Router\RouterException
     *   Any of the Required Parameters has an invalid value from a
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
     *   Array of Params to parse
     *
     * @param string $URI
     *   Request URI to be checked against
     *
     * @return array
     *   An array with all parsed Dynamic Parameters and their proper values
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
                 * by Next\Controller\Router\Standard::lookup(), we'll use the
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
     *   Parameter Data
     *
     * @param string $value
     *   Parameter Value
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

    // Auxiliary Methods

    /**
     * Extends SQLITE functionality adding a UDF (User Defined Function)
     * for REGEXP keyword use
     *
     * @throws Next\Controller\Router\RouterException
     *   Current Database Connection Adapter doesn't have the
     *   sqliteCreateFunction() method
     */
    private function extend() {

        if( ! method_exists( $this -> dbh, 'sqliteCreateFunction' ) ) {

            throw RouterException::unfullfilledRequirements(

                'PDO::sqliteCreateFunction() doesn\'t exist in current Database Handler so you are not able to use Standard Router'
            );
        }

        $this -> dbh
              -> sqliteCreateFunction(

                  'REGEXP',

                  function( $r, $s ) {

                      return ( preg_match( sprintf( '@^%s$@i', $r ), $s ) != 0 );
                  },

                  2
              );
    }
}