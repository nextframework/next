<?php

namespace Next\Controller\Router;

use Next\Application\Application;    # Application Interface
use Next\HTTP\Request;               # Request Class

/**
 * Standard Controller Router Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class SQLite extends Standard {

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

        parent::init();

        // Extending SQLITE Custom Resources

        $this -> createFunction();
    }

    /**
     * Finds a Route that matches to an Application AND current Request
     *
     * @param Next\Application\Application $application
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
     *  SQLITE Database File doesn't exist in defined directory
     */
    protected function connect() {

        // Checking if Database File Exists

        if( ! file_exists( $this -> options -> dbPath ) ) {

            throw RouterException::connectionFailure(

                'Routes Database File %s doesn\'t exist in Data Directory',

                RouterException::CONNECTION_FAILED,

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
     *              <p>Default Value: <strong>data/routes.sqlite</strong></p>
     *
     *          </li>
     *
     *      </ul>
     *
     *  </p>
     */
    public function setOptions() {

        return array(

            'dbPath' => 'data/routes.sqlite'
        );
    }

    // Auxiliary Methods

    /**
     * Extends SQLITE functionality adding a UDF (User Defined Function)
     * for REGEXP keyword use
     *
     * @throws Next\Controller\Router\RouterException
     *  Current Database Connection Adapter doesn't have the
     *  sqliteCreateFunction() method
     */
    private function createFunction() {

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