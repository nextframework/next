<?php

/**
 * Database Driver Interface | DB\Driver\Driver.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\Driver;

/**
 * Connection Driver Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Driver {

    /**
     * Establishes a Database Connection
     */
    public function connect();

    /**
     * Disestablishes a Database Connection
     */
    public function disconnect();

    /**
     * Checks if there's a Database Connection Link active
     */
    public function isConnected();

    /**
     * Get Database Connection Link
     */
    public function getConnection();

    // Query-related Methods

    /**
     * Executes an SQL statement
     *
     * @param string $statement
     *  Query Statement
     *
     * @see \Next\DB\Statement\Statement
     */
    public function query( $statement );

    /**
     * Prepares an SQL Statement
     *
     * @param string $statement
     *  Statement to be prepared
     *
     * @see \Next\DB\Statement\Statement
     */
    public function prepare( $statement );

    /**
     * Get Last inserted ID.
     * Returns the ID of the last inserted row or sequence value
     *
     * @param string|optional $name
     *  Name of the sequence object from which the ID should be returned.
     *  Used by PDO_PGSQL, for example (according to manual)
     */
    public function lastInsertId( $name = NULL );

    // Accessory Methods

    /**
     * Get an SQL Statement Renderer
     */
    public function getRenderer();
}
