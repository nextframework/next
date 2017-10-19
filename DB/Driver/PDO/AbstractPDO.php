<?php

/**
 * PDO Driver Abstract Class | DB\Driver\PDO\AbstractPDO.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\Driver\PDO;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\RuntimeException;

use Next\Components\Interfaces\Verifiable;      # Verifiable Interface
use Next\Components\Interfaces\Configurable;    # Configurable Interface
use Next\DB\Driver\Driver;                      # Driver Interface

use Next\Components\Object;                     # Object Class

/**
 * PDO Driver Abstract Adapter Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractPDO extends Object implements Verifiable, Driver {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'username' => [ 'required' => FALSE, 'default' => 'root' ],
        'password' => [ 'required' => FALSE, 'default' => '' ]
    ];

    /**
     * Connection Object
     *
     * @var mixed $connection
     */
    protected $connection;

    // Driver Interface Methods Implementation

    /**
     * Establishes a Database Connection
     *
     * @return PDO
     *  PDO Connection Link
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown with \PDOException's message if one is caught
     */
    public function connect() {

        try {

            $this -> connection = new \PDO(

                $this -> getDSN(),

                $this -> options -> username,

                $this -> options -> password
            );

            // Connection Attributes

                // Forcing PDOException's to be thrown

            $this -> connection -> setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );

                // Forcing Statements to return Objects

            $this -> connection -> setAttribute( \PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ );

                // Disabling Prepared Statements Emulation

            $this -> connection -> setAttribute( \PDO::ATTR_EMULATE_PREPARES, FALSE );

                // Using a custom Statement Class

            $this -> connection -> setAttribute(

                \PDO::ATTR_STATEMENT_CLASS,

                [
                  'Next\DB\Statement\PDO\Statement',

                  [ new \Next\DB\Statement\PDO\Adapter( [ 'driver' => $this ] ) ]
                ]
            );

            /**
             * Running post-initialization Configuration, if needed
             */
            if( $this instanceof Configurable ) $this -> configure();

            return $this -> connection;

        } catch( \PDOException $e ) {
            throw new RuntimeException( $e -> getMessage() );
        }
    }

    /**
     * Disestablishes a Database Connection
     */
    public function disconnect() {

        /**
         * @internal
         *
         * PDO closes itself automatically ^^
         * But we must unset the Connection Adapter defined
         */
        $this -> connection = NULL;
    }

    /**
     * Checks if there's a Database Connection Link active
     *
     * @return boolean
     *  TRUE if we have a valid connection and FALSE otherwise
     */
    public function isConnected() {
        return ( $this -> connection instanceof \PDO );
    }

    /**
     * Get Database Connection Link
     *
     * @return mixed
     *  Database Connection Link
     */
    public function getConnection() {

        // Connecting if needed

        if( ! $this -> isConnected() ) {
            $this -> connect();
        }

        return $this -> connection;
    }

        // Query-related Methods

    /**
     * Executes an SQL statement
     *
     * @param string $statement
     *  Query Statement
     *
     * @return \Next\DB\Statement\Statement
     *  Statement Object
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown with \PDOException's message if one is caught
     *
     * @see \Next\DB\Statement\Statement
     */
    public function query( $statement ) {

        try {

            return $this -> getConnection() -> query( (string) $statement );

        } catch( \PDOException $e ) {
            throw new RuntimeException( $e -> getMessage() );
        }
    }

    /**
     * Prepares an SQL Statement
     *
     * @param string $statement
     *  Statement to be prepared
     *
     * @return \Next\DB\Statement\Statement
     *  Statement Object
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown with \PDOException's message if one is caught
     *
     * @see \Next\DB\Statement\Statement
     */
    public function prepare( $statement ) {

        try {

            return $this -> getConnection() -> prepare( (string) $statement );

        } catch( \PDOException $e ) {
            throw new RuntimeException( $e -> getMessage() );
        }
    }

    /**
     * Get Last inserted ID.
     * Returns the ID of the last inserted row or sequence value
     *
     * @param string $name
     *  Name of the sequence object from which the ID should be returned
     *  According to PHP Manual it's used, for example, by PDO_PGSQL
     *  as sequence object identifier
     *
     * @return integer|string
     *  ID of last inserted record
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown with \PDOException's message if one is caught
     */
    public function lastInsertId( $name = NULL ) {

        try {

            return $this -> getConnection() -> lastInsertId( $name );

        } catch( \PDOException $e ) {
            throw new RuntimeException( $e -> getMessage() );
        }
    }

    // Verifiable Interface Method Implementation

    /**
     * Verifies Object Integrity.
     * Checks if PDO Extension has been loaded
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown if PDO Extension has not been loaded
     */
    public function verify() {

        if( ! extension_loaded( 'pdo' ) ) {
            throw new RuntimeException( 'PDO Extension not loaded' );
        }
    }

    // Abstract Methods Definition

    /**
     * Get Connection Adapter DSN
     */
    abstract protected function getDSN();
}
