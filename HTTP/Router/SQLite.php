<?php

/**
 * HTTP Request "SQLite" Router Class | HTTP\Router\SQLite.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Router;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\BadMethodCallException;

use Next\Validation\Verifiable;                       # Verifiable Interface
use Next\Application\Application;                     # Application Interface

use Next\Components\Object;                           # Object Class
use Next\Components\Parameter;                        # Parameter Class
use Next\HTTP\Request;                                # Request Class
use Next\DB\Driver\PDO\Adapter\SQLite as Adapter;     # SQLite DB Adapter

/**
 * HTTP Request Router based on SQLite Databases.
 * It uses most of the logics of the Standard Router as it derives only the
 * way of finding the Routes, having the same parser
 *
 * @package    Next\HTTP
 *
 * @uses       Next\Exception\Exceptions\BadMethodCallException
 *             Next\Validation\Verifiable
 *             Next\Application\Application
 *             Next\Components\Object
 *             Next\Components\Parameter
 *             Next\HTTP\Request
 *             Next\DB\Driver\PDO\Adapter\SQLite
 */
class SQLite extends Standard {

    /**
     * SQLite Connection Resource
     *
     * @var mixed $dbh
     */
    protected $dbh;

    /**
     * Additional Initialization.
     * Creates an SQLIte Database Connection and extends the
     * functionality of SQLite pseudo-language
     *
     * @see SQLite::connect()
     * @see SQLite::createFunction()
     */
    protected function init() : void {

        $this -> connect();

        $this -> createFunction();
    }

    // Router Interface Method Implementation

    /**
     * Finds a matching Route for the Application -AND- current Request URI
     *
     * @return array|object|boolean
     *
     *  If a Route could be match against current Request URI an
     *  array or an object will be returned (depending on Connection
     *  Driver configuration).
     *
     *  If none could, NULL is returned
     */
    public function find() :? Parameter {

        // Shortening Declarations

        $request = $this -> options -> application -> getRequest();

        $URI = $request -> getURL( FALSE );

        // Removing trailing slash if on slash (/) route

        if( $request -> getRequestURI() == '/' ) {
            $URI = rtrim( $URI, '/' );
        }

        /**
         * Searching the Request in Routes Database
         *
         * @internal
         *
         * To be a matching route it must:
         *
         * - Belong to the same Application (classname)
         * - Correspond to the same Request Method of
         *   the current Request
         * - Have its route RegExp matching the Request URI
         *   of current Request
         */
        $stmt = $this -> dbh -> prepare( 'SELECT `requestMethod`, `controller`, `method`,
                                                 `requiredParams`, `optionalParams`
                                            FROM `routes`
                                                WHERE `application` = ? AND
                                                    ( `requestMethod` = ? AND ? REGEXP `URI` )' );

        $stmt -> execute(

            [

                $this -> options -> application -> getClass() -> getName(),

                $request -> getRequestMethod(),

                $URI
            ]
        );

        $data = $stmt -> fetch();

        // Match found, let's prepare everything for a successful Dispatch

        if( $data === FALSE ) return NULL;

        /**
         * @internal
         *
         * $data is being rewritten to point to the first in the
         * filtered dataset because this contains the most specific
         * Route, counter-balancing the lack of gluttony
         * of preg_match() that doesn't match as much as possible
         */
        $data = Object::map( ( (array) $data === $data ? array_shift( $data ) : $data ) );

        /**
         * @internal
         *
         * Setting Up Found Controller and its action method to be used
         * in View, as part of Template File Detection by FileSpec
         */
        $this -> controller = $data -> controller;
        $this -> method     = $data -> method;

        // Analyzing Params

        $requiredParams = unserialize( $data -> requiredParams );

        // Lookup for Required Params in URL

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

                function( $current ) : bool {

                    return ( ! empty( $current['acceptable'] ) ||
                                ! empty( $current['regex'] ) );
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

        return $data;
    }

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Filepath to the SQLite Database File was not informed or it's empty
     */
    public function verify() : void {

        // Checking if Database File Exists

        if( $this -> options -> dbPath === FALSE || ! file_exists( $this -> options -> dbPath ) ) {

            throw new InvalidArgumentException(

                sprintf(

                    'SQLite Database File <strong>%s</strong> doesn\'t exist',

                    $this -> options -> dbPath
                )
            );
        }
    }

    // Parameterizable Interface Method Overwriting

    /**
     * Set Class Options.
     * Defines a default dbpath for the SQLite Database with Generated Routes
     */
    public function setOptions() : array {
        return [ 'dbPath' => __DIR__ . '/routes.sqlite' ];
    }

    // Auxiliary Methods

    /**
     * Establishes a Connection with the SQLITE Database File
     */
    private function connect() : void {

        $adapter = new Adapter(
           [ 'dbPath' => $this -> options -> dbPath ]
        );

        $this -> dbh = $adapter -> getConnection();
    }

    /**
     * Extends SQLITE functionality adding a UDF (User Defined Function)
     * for REGEXP keyword use
     *
     * @throws \Next\Exception\Exceptions\BadMethodCallException
     *  Current Database Connection Adapter doesn't have the
     *  sqliteCreateFunction() method
     */
    private function createFunction() : void {

        if( ! method_exists( $this -> dbh, 'sqliteCreateFunction' ) ) {

            throw new BadMethodCallException(

                'PDO::sqliteCreateFunction() doesn\'t exist in current
                Database Handler so the REGEXP Operator can\'t be created'
            );
        }

        $this -> dbh
              -> sqliteCreateFunction(

                  'REGEXP',

                  function( $r, $s ) : bool {
                      return ( preg_match( sprintf( '@^%s$@i', $r ), $s ) != 0 );
                  },

                  2
              );
    }
}