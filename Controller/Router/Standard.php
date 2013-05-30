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
     * List of Arguments beginning Token
     *
     * @var string
     */
    const LIST_OPEN_TOKEN       = '[';

    /**
     * RegExp for Default Value
     *
     * @var string
     */
    const DEFAULT_VALUE_REGEXP  = '<(.*?)>';

    /**
     * RegExp for Valid Values
     *
     * @var string
     */
    const VALID_VALUES_REGEXP   = '\[(.*?)\]';

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
                                                    ( `requestMethod` = ? AND ? REGEXP `URI` )' );

        $stmt -> execute(

            array(

                $application -> getClass() -> getName(),

                $request -> getRequestMethod(),

                $URI
            )
        );

        $data = $stmt -> fetch();

        // Match found, let's prepare everything for a successful Dispatch

        if( $data !== FALSE ) {

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
             * Only Parameters with a [List|of|Possible|Values] will be validated
             */
            $token = self::LIST_OPEN_TOKEN;

            $this -> validate(

                array_filter(

                    $requiredParams,

                    function( $item ) use( $token ) {

                        return ( strpos( $item, $token ) !== FALSE );
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

        $token = self::LIST_OPEN_TOKEN;

        /**
         * Merging manually defined GET query so they can
         * be considered as validatable arguments too
         */
        if( count( $queryData ) != 0 ) {
            $params = array_merge( $params, array_keys( $queryData ) );
        }

        array_walk( $params,

            function( $param ) use( $URI, $token ) {

                /**
                 * @internal
                 * Removing possible [List|of|Possible|Values]
                 * because here we're just checking the existence of Parameter in Requested URI
                 */
                if( strpos( $param, $token ) !== FALSE ) {
                    $param = substr_replace( $param, '', strpos( $param, $token ) );
                }

                /**
                 * @internal
                 * Removing any non alpha characters, mainly, but not strictly,
                 * our "special" token: $ (required parameters) and : (optional parameters)
                 */
                $param = preg_replace( '/\W/', '', $param );

                preg_match( sprintf( '/\/%s\/.+\/?/', $param ), $URI, $matches );

                // Not present in URL and neither in GET superglobal?

                if( count( $matches ) == 0 && ! isset( $_GET[ $param ] ) ) {

                    // Fine! Let's also send a 400 Response Header (Bad Request)

                    throw RouterException::missingParameter( $param );
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

        // If we don't have params [due empty array_filter() ], we have nothing to work

        if( count( $params ) == 0 ) {
            return;
        }

        $tokens = array(

            self::VALID_VALUES_REGEXP,
            self::LIST_OPEN_TOKEN,
            self::SEPARATOR_TOKEN,
            self::DEFAULT_VALUE_REGEXP
        );

        array_walk(

            $params,

            function( $param ) use( $tokens, $URI ) {

                // Finding the [List|of|Possible|Values]

                preg_match( sprintf( '/%s/', $tokens[ 0 ] ), $param, $valid );

                // Cleaning Parameter Declaration...

                $param = preg_replace(

                    '/\W/', '',

                    substr_replace( $param, '', strpos( $param, $tokens[ 1 ] ) )
                );

                if( ! empty( $param ) && isset( $valid[ 1 ] ) ) {

                    // ... to get its Value

                    preg_match(

                        sprintf( '/\/%s\/([^\/]+)\/?/', $param ), $URI, $value
                    );

                    // If Parameter is not in the list...

                    if( ! in_array( $value[ 1 ], explode( $tokens[ 2 ], $valid[ 1 ] ) ) ) {

                        // ... and there is no Default Value defined for it

                        if( preg_match( sprintf( '/%s/', $tokens[ 3 ] ), $valid[ 1 ] ) == 0 ) {

                            // Teh, he, he ^^

                            throw RouterException::invalidParameter( $param );
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

        // If we don't have params to parse, we will not parse >.<

        if( count( $params ) == 0 ) {
            return array();
        }

        $pairs = array();

        $processed = array();

        foreach( $params as $param ) {

            /**
             * @internal
             * If current Parameter (in iteration) is present in URL,
             * let's get its value.
             *
             * We don't need to care if the parameter MUST be present,
             * because all the missing Required Parameters were already
             * validated in Next\Controller\Router\Standard::lookup()
             *
             * We'll save in a temp property the clean Parameter name
             */
            $temp = $param;

            if( strpos( $temp, self::LIST_OPEN_TOKEN ) !== FALSE ) {

                $temp = substr_replace(

                    $temp, '', strpos( $temp, self::LIST_OPEN_TOKEN )
                );
            }

            // Removing all Tokens

            $temp = preg_replace( '/\W/', '', $temp );

            // Listing Parameters Values

            preg_match( sprintf( '/\/%s\/([^\/]+)\/?/', $temp ), $URI, $value );

            /**
             * @internal
             * Fixing preg_match()'s laziness
             *
             * When param's value is the name of param too, the preg_match() above
             * will match the value as a param too.
             *
             * Let's consider a Search System with options passed through URL (GET)
             *
             * This Search System has three route params:
             *
             * - order   => to order the results;
             * - sort    => to define how the ordenation will be done (asc/desc);
             * - name    => to filter the results by a specific name (or names)
             *
             * The database structure has only two fields: "id" and "name"
             *
             * We want to sort, downwardly, by name. So we would access the
             * Search Results' Page as domain.com/order/name/sort/desc
             *
             * Due preg_match()'s laziness we would have a wrongly matched route param
             * called "name" with "sort" as value, because the field we want to order (name)
             * is one of defined Route's Params too
             */
            if( isset( $value[ 1 ] ) ) {

                // This is required because preg_match_all() give us an array

                $test = ( is_array( $value[ 1 ] ) ? current( $value[ 1 ] ) : $value[ 1 ] );

                if( in_array( $test, $processed ) ) continue;
            }

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
                $pairs[ $temp ] = $this -> getDefaultValue( $param );
            }

            // Complement to big explanation above :P

            $processed[] = $temp;
        }

        return $pairs;
    }

    // Auxiliary Methods

    /**
     * Find Parameter Value
     *
     * @param string $param
     *   Parameter Name
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

        $return = array();

        // If we have a List to check against it

        if( strpos( $param, self::LIST_OPEN_TOKEN ) !== FALSE ) {

            // ... we'll list the valid values...

            preg_match(

                sprintf( '/%s/', self::VALID_VALUES_REGEXP ), $param, $valid
            );

            // ... and if we have a List...

            if( isset( $valid[ 1 ] ) ) {

                // ... we'll check against it

                return $this -> compareWithList( $value, $valid[ 1 ] );
            }

        } else {

            // If we don't have a List, let's accept the value "as is"

            return $value;
        }
    }

    /**
     * Compare given value in a [List|of|Possible|Values]
     *
     * @param string $value
     *   Value to compare
     *
     * @param string $list
     *   List to compare given value
     *
     * @return string|NULL
     *
     *   - If given parameter lacks the Separator Token OR
     *     if it IS present in a List of Possible Values, it will be returned "as is"
     *
     *   - Otherwise we'll try to find the proper Default Value.
     *     If we succeed, this value will be returned. Otherwise NULL will
     */
    private function compareWithList( $value, $list ) {

        // Mal-formed List?

        if( strpos( $list, self::SEPARATOR_TOKEN ) === FALSE ) {

            // Let's use what we received

            return $value;

        } else {

            // Parameter is in the List?

                // Removing Default Value Definition of given list in order to split correctly

            $l = preg_replace(

                sprintf( '/%s/', self::DEFAULT_VALUE_REGEXP ), '', $list
            );

            if( ArrayUtils::in( $value, explode( self::SEPARATOR_TOKEN, $l ) ) ) {
                return $value;
            }

            // No? Let's try to find the Default Value

            return $this -> getDefaultValue( $list );
        }
    }

    /**
     * Get Default Parameter Value
     *
     * @param string $list
     *   Get Default Value from a list of possible values
     *
     * @return string|NULL
     *
     *   We'll try to find the proper Default Value.
     *   If we succeed, this value will be returned. Otherwise NULL will
     */
    private function getDefaultValue( $list ) {

        preg_match(

            sprintf( '/%s/', self::DEFAULT_VALUE_REGEXP ), $list, $default
        );

        return ( count( $default ) > 0 ? $default[ 1 ] : NULL );
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