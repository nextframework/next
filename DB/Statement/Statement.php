<?php

/**
 * Database Statement Interface | DB\Statement\Statement.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\Statement;

/**
 * An Interface for all Statement Classes
 *
 * @package    Next\DB
 */
interface Statement {

    // Execution-related Methods

    /**
     * Execute a prepared statement.
     *
     * @param array|optional $params
     *  Values to bind to parameter placeholders.
     */
    public function execute( array $params = [] ) : bool;

    /**
     * Fetch the next row from a ResultSet
     *
     * @param integer|optional $style
     *  Fetch mode for this fetch operation
     *
     * @param integer|optional $cursor
     *  Determines which row will be returned to the caller
     *
     * @param integer|optional $offset
     *  Controls the cursor orientation
     */
    public function fetch( $style = null, $cursor = null, $offset = null );

    /**
     * Return an array containing all of the ResultSet rows
     *
     * @param integer|optional $style
     *  Fetch mode for this fetch operation
     */
    public function fetchAll( $style = null );

    /**
     * Return a single column from the next row of a ResultSet
     *
     * @param integer|optional $column
     *  Position of the column to fetch
     */
    public function fetchColumn( $column = 0 );

    /**
     * Return the number of rows affected by the last SQL statement
     */
    public function rowCount() : int;

    // Exception-related Methods

    /**
     * Retrieve the Error Code
     */
    public function errorCode() : string;

    /**
     * Retrieve an array of error Information
     */
    public function errorInfo() : array;

    /**
     * Set the default Fetch Mode for Statement
     *
     * @param integer|optional $mode
     *  The Fetch Mode
     *
     * @param mixed|array|optional $params
     *  Additional Parameters
     */
    public function setFetchMode( $mode, $params = NULL ) : bool;

    // Miscellaneous Methods

    /**
     * Close the cursor
     *
     * This enables the statement to be executed again.
     */
    public function closeCursor() : bool;
}
