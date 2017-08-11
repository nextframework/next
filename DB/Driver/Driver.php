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

    // Basic Stuff

    /**
     * Connect
     */
    public function connect();

    /**
     * Disconnect
     */
    public function disconnect();

    /**
     * Check if it's Connected
     */
    public function isConnected();

    // Query-related Methods

    /**
     * Execute an SQL statement
     *
     * @param string $statement
     *  Query Statement
     *
     * @see \Next\DB\Statement\Statement
     */
    public function query( $statement );

    /**
     * Prepare an SQL Statement
     *
     * @param string $statement
     *  Statement to be prepared
     *
     * @see \Next\DB\Statement\Statement
     */
    public function prepare( $statement );

    /**
     * Get Last inserted ID
     *
     * Returns the ID of the last inserted row or sequence value
     *
     * @param string|optional $name
     *
     *   <p>
     *       Name of the sequence object from which the ID should be returned.
     *   </p>
     *
     *   <p>Used by PDO_PGSQL, for example (according to manual)</p>
     */
    public function lastInsertId( $name = NULL );

    // Accessors

    /**
     * Get an SQL Statement Renderer
     */
    public function getRenderer();
}
