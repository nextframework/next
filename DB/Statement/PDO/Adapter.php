<?php

/**
 * PDO Statement Adapter Class | DB\Statement\PDO\Adapter.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\Statement\PDO;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\RuntimeException;
use Next\Exception\Exceptions\BadMethodCallException;

use Next\DB\Statement\Statement;    # DB Statement Interface
use Next\Components\Object;         # Object Class

/**
 * The PDO Statement Adapter adapts the native PDOStatement interface signature
 *
 * @package    Next\DB
 *
 * @uses       Next\Exception\Exceptions\RuntimeException
 *             Next\Exception\Exceptions\BadMethodCallException
 *             Next\DB\Statement\Statement
 *             Next\Components\Object
 *             PDOStatement
 *             PDOException
 *             ReflectionMethod
 *             ReflectionException
 */
class Adapter extends Object implements Statement {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'driver' => [ 'type' => 'Next\DB\Driver\Driver', 'required' => TRUE ],
    ];

    /**
     * PDOStatement Object
     *
     * @var PDOStatement $stmt
     */
    private $stmt;

    /**
     * Set a PDOStatement Object which will be adapted
     *
     * @internal
     * This method must always exist
     * The right way to ensure this would be through Interfaces o
     * Abstract Class, but none of these fits without tie the API
     *
     * @param PDOStatement $stmt
     *
     * @return \Next\DB\Statement\PDO\Adapter
     *  Statement Adapter Object (Fluent Interface)
     */
    public function setStatement( \PDOStatement $stmt ) : Adapter {

        $this -> stmt = $stmt;

        return $this;
    }

    // Statement Interface Methods Implementation

        // Execution-related Methods

    /**
     * Execute a prepared statement.
     *
     * @param array|optional $params
     *  Values to bind to parameter placeholders
     *
     * @return boolean
     *  TRUE on success and FALSE on failure
     */
    public function execute( array $params = [] ) : bool {
        return $this -> invoke( 'execute', $params );
    }

    /**
     * Fetch the next row from a ResultSet
     *
     * @param integer|optional $style
     *  Fetch mode for this fetch operation.
     *  Not directly used (documentation only)
     *
     * @param integer|optional $cursor
     *  Determines which row will be returned to the caller
     *  Not directly used (documentation only)
     *
     * @param integer|optional $offset
     *  Controls the cursor orientation
     *  Not directly used (documentation only)
     *
     * @return array|stdClass|boolean
     *  An array or an stdClass object on success, depending
     *  on <strong>$style</strong> argument and FALSE otherwise
     */
    public function fetch( $style = null, $cursor = null, $offset = null ) {
        return $this -> invoke( 'fetch', func_get_args() );
    }

    /**
     * Return an array containing all of the ResultSet rows
     *
     * @param integer|optional $style
     *  Fetch mode for this fetch operation
     *  Not directly used (documentation only)
     *
     * @return array|object|boolean
     *  An array or an stdClass object on success, depending
     *  on <strong>$style</strong> argument and FALSE otherwise
     */
    public function fetchAll( $style = null ) {
        return $this -> invoke( 'fetchAll', func_get_args() );
    }

    /**
     * Return a single column from the next row of a ResultSet
     *
     * @param integer|optional $column
     *  Position of the column to fetch
     *
     * @return string|boolean
     *  The next column value of a ResultSet or FALSE if there are no more rows
     */
    public function fetchColumn( $column = 0 ) {
        return $this -> invoke( 'fetchColumn', $column );
    }

    /**
     * Return the number of rows affected by the last SQL statement
     *
     * @return integer
     *  The number of rows affected
     */
    public function rowCount() : int {
        return $this -> invoke( 'rowCount' );
    }

        // Exception-related Methods

    /**
     * Retrieve the Error Code
     *
     * @return string
     *  The SQLSTATE Error Code of performed PDOStatement operation
     */
    public function errorCode() : string {
        return $this -> invoke( 'errorCode' );
    }

    /**
     * Retrieve an array of error information
     *
     * @return array
     *  Extended error information
     */
    public function errorInfo() : array {
        return $this-> invoke( 'errorInfo' );
    }

    /**
     * Set the default Fetch Mode for the Statement
     *
     * @param integer|optional $mode
     *  The Fetch Mode
     *
     * @param mixed|array|optional $params
     *  Additional Parameters
     *
     * @return boolean
     *  TRUE on success and FALSE otherwise
     */
    public function setFetchMode( $mode, $params = NULL ) : bool {
        return $this -> invoke( 'setFetchMode', [ $mode, $params ] );
    }

    // Miscellaneous Methods

    /**
     * Closes the cursor
     *
     * This enables the statement to be executed again
     *
     * @return boolean
     *  TRUE on success and FALSE otherwise
     */
    public function closeCursor() : bool {
        return $this -> invoke( 'closeCursor' );
    }

    // Auxiliary Methods

    /**
     * Call a PDOStatement Method from PDO Adapter Context,
     * adapting native PDOStatement Interface to our Statement Interface
     *
     * @param string $method
     *  Method trying to be invoked
     *
     * @param array|optional $args
     *  Variable list of arguments to the method, if exist
     *
     * @return mixed|void
     *  Returns what called PDOStatement's method returns and
     *  FALSE otherwise
     *
     * @throws \Next\Exception\Exceptions\BadMethodCallException
     *  Thrown with \PDOException's message if one is caught
     *
     * @throws \Next\Exception\Exceptions\RuntimeException
     *  Thrown with \PDOException's message if one is caught
     */
    private function invoke( $method, $args = [] ) {

        try{

            /**
             * @internal
             *
             * The trick here is to reflect over Statement parent class,
             * even if PDOStatement has no parent class
             *
             * And then call <strong>$method</strong> from THIS context
             */
            $reflector = new \ReflectionMethod( get_parent_class( $this -> stmt ), $method );

            return $reflector -> invokeArgs(
                $this -> stmt, array_filter( (array) $args )
            );

        } catch( \ReflectionException $e ) {

            throw new BadFunctionException(

                sprintf(

                    '<em>PDO::%s</em> could not be invoked

                    The following error has been returned: %s',

                    $method, $e -> getMessage()
                )
            );

        } catch( \PDOException $e ) {

            throw new RuntimeException( $e -> getMessage() );
        }
    }
}