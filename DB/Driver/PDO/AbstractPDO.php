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

use Next\Components\Interfaces\Configurable;    # Configurable Interface

use Next\DB\Driver\AbstractDriver;              # Connection Driver Abstract Class

/**
 * PDO Driver Abstract Adapter Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractPDO extends AbstractDriver {

    // Adapter Interface Methods Implementation

    /**
     * Connect
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
     * Disconnect
     */
    public function disconnect() {

        /**
         * @internal
         * PDO closes itself automatically ^^
         * But we must unset the Connection Adapter defined
         */
        $this -> connection = NULL;
    }

    /**
     * Check if it's Connected
     *
     * @return boolean
     *  TRUE if we have a valid connection and FALSE otherwise
     */
    public function isConnected() {
        return ( $this -> connection instanceof \PDO );
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
     */
    public function prepare( $statement ) {

        try {

            return $this -> getConnection() -> prepare( (string) $statement );

        } catch( \PDOException $e ) {
            throw new RuntimeException( $e -> getMessage() );
        }
    }

    /**
     * Get Last inserted ID
     *
     * @param string|optional $name
     *
     *   <p>
     *       Name of the sequence object from which the ID should be returned.
     *   </p>
     *
     *   <p>Used by PDO_PGSQL, for example (according to manual)</p>
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

    // Abstract Methods Definition

    /**
     * Get Connection Adapter DSN
     */
    abstract protected function getDSN();

    // Abstract Methods Implementation

    /**
     * Connection Driver Extra initialization
     *
     * <p>Driver-specific extra initialization</p>
     *
     * <p>
     *     Implemented here because not all the drivers requires extra
     *     initialization
     * </p>
     */
    protected function configure() {}

    /**
     * Check for Connection Driver Requirements
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  PDO Extension was not loaded
     */
    protected function checkRequirements() {

        if( ! extension_loaded( 'pdo' ) ) {
            throw new RuntimeException( 'PDO Extension not loaded' );
        }
    }
}
