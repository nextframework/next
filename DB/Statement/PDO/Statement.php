<?php

namespace Next\DB\Statement\PDO;

use Next\DB\Statement\Statement as StatementInterface;    # Statement Interface

/**
 * PDOStatement derived Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Statement extends \PDOStatement {

    /**
     * Statement Adapter
     *
     * @var Next\DB\Statement\Statement $adapter
     */
    private $adapter;

    /**
     * PDOStatement Constructor
     *
     * @param Next\DB\Statement\Statement $adapter
     *   Statement Adapter
     */
    private function __construct( StatementInterface $adapter ) {

        $adapter -> setStatement( $this );

        $this -> adapter =& $adapter;
    }

    // Execution-related Methods

    /**
     * Execute a prepared statement.
     *
     * @param array|optional $input_parameters
     *   Values to bind to parameter placeholders.
     *
     * @return boolean TRUE on success and FALSE on failure
     */
    public function execute( $input_parameters = NULL ) {
        return $this -> adapter -> execute( array( $input_parameters ) );
    }

    /**
     * Fetch the next row from a ResultSet
     *
     * @param integer|optional $fetch_style
     *   Fetch mode for this fetch operation
     *
     * @param integer|optional $cursor_orientation
     *   Determines which row will be returned to the caller
     *
     * @param integer|optional $cursor_offset
     *   Controls the cursor orientation
     *
     * @return array|stdClass|boolean
     *   An array or an stdClass object on success, depending
     *   on <strong>$style</strong> argument and FALSE otherwise
     */
    public function fetch( $fetch_style = null, $cursor_orientation = null, $cursor_offset = null ) {
        return $this -> adapter -> fetch( $fetch_style );
    }

    /**
     * Return an array containing all of the ResultSet rows
     *
     * @param integer|optional $fetch_style
     *   Fetch mode for this fetch operation
     *
     * @param mixed|optional $fetch_argument
     *   Complementary argument to Fetch Style
     *
     * @param array|optional $ctor_args
     *
     *   <p>Arguments passed to a Class Constructor.</p>
     *
     *   <p>
     *     Used only when <strong>$fetch_Style</strong> is
     *     <em>PDO::FETCH_CLASS</em>
     *   </p>
     *
     * @return array|stdClass|boolean
     *   An array or an stdClass object on success, depending
     *   on <strong>$style</strong> argument and FALSE otherwise
     */
    public function fetchAll( $fetch_style = NULL, $fetch_argument = NULL, $ctor_args = NULL ) {
        return $this -> adapter -> fetchAll( $fetch_style );
    }

    /**
     * Return a single column from the next row of a ResultSet
     *
     * @param integer|optional $column_number
     *   Position of the column to fetch
     *
     * @return string|boolean
     *   The next column value of a ResultSet or FALSE if there are no more rows
     */
    public function fetchColumn( $column_number = 0 ) {
        return $this -> adapter -> fetchColumn( $column_number );
    }

    /**
     * Return the number of rows affected by the last SQL statement
     *
     * @return integer
     *   The number of rows affected
     */
    public function rowCount() {
        return $this -> adapter -> rowCount();
    }

    // Exception-related Methods

    /**
     * Retrieve the Error Code
     *
     * @return string The SQLSTATE Error Code of performed PDOStatement operation
     */
    public function errorCode() {
        return $this -> adapter -> errorCode();
    }

    /**
     * Retrieve an array of error Information
     *
     * @return array Extended error Information
     */
    public function errorInfo() {
        return $this -> adapter -> errorInfo();
    }

    /**
     * Set the default Fetch Mode for the Statement
     *
     * @param integer|optional $fetchStyle
     *   The Fetch Mode
     *
     * @param mixed|array|optional $params
     *   Additional Parameters
     *
     * @return boolean
     *   TRUE on success and FALSE otherwise
     */
    public function setFetchMode( $fetchStyle, $params = NULL ) {
        return $this -> adapter -> setFetchMode( $fetchStyle );
    }

    // Miscellaneous Methods

    /**
     * Closes the cursor
     *
     * This enables the statement to be executed again
     *
     * @return boolean
     *   TRUE on success and FALSE otherwise
     */
    public function closeCursor() {
        return $this -> adapter -> closeCursor();
    }
}
