<?php

namespace Next\DB\Table;

use Next\DB\Driver\DriverException;             # Driver Exception Class
use Next\DB\Statement\StatementException;       # Statement Exception Class
use Next\Components\Object;                     # Object Class
use Next\Components\Invoker;                    # Invoker Class
use Next\DB\Driver\Driver;                      # Connection Driver Interface
use Next\DB\Query\Query;                        # Query Interface
use Next\DB\Query\Builder;                      # Query Builder Class
use Next\DB\Table\Row, Next\DB\Table\RowSet;    # Row and RowSet Classes

/**
 * Table Manager Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Manager extends Object {

    /**
     * Connection Driver
     *
     * @var Next\DB\Driver\Driver $driver
     */
    private $driver;

    /**
     * Table Object
     *
     * @var Next\DB\Table\Table $table
     */
    private $table;

    /**
     * Query Builder
     *
     * @var Next\DB\Query\Builder $builder
     */
    private $builder;

    /**
     * Data Source
     *
     * An associative array where the keys are Table Columns and
     * their associated values
     *
     * @var array $source
     */
    private $source = NULL;

    /**
     * Table Manager Constructor
     *
     * @param Next\DB\Driver\Driver $driver
     *  Connection Driver
     *
     * @param Next\DB\Table\Table $table
     *  Table Object
     */
    public function __construct( Driver $driver, Table $table ) {

        parent::__construct();

        // Setting Up resources

        $this -> driver =& $driver;

        $this -> table  =& $table;

        $this -> builder = new Builder( $driver -> getRenderer() );

        /**
         * @internal Data Source
         *
         * By default Table Manager will work with original Table Fields.
         *
         * When a UPDATE Statement is executed, however, the Manager will
         * work with Row/RowSet Fields computed from the difference between
         * original fields and modified fields
         */
        $this -> source = $table -> getFields();

        // Extend Object Context to QueryBuilder Class

        $this -> extend( new Invoker( $this, $this -> builder ) );
    }

    /**
     * Set Data Source
     *
     * @param array $source
     *  Data Source
     *
     * @return Next\DB\Table\Manager
     *  Table Manager Object (Fluent Interface)
     */
    public function setSource( array $source ) {

        $this -> source =& $source;

        return $this;
    }

    // Statement-related Accessors

    /**
     * Return the number of rows affected by the last SQL statement
     *
     * @return integer
     *  The number of rows affected
     *
     * @see Next\DB\Driver\Driver::lastInsertId()
     */
    public function rowCount() {
        return $this -> execute() -> rowCount();
    }

    /**
     * Fetch the next row from a result set
     *
     * This method is a mere formality since you shouldn't get a Row outside
     * a RowSet
     *
     * @param string|integer|optional $fetchStyle
     *  The Fetch Mode, accordingly to chosen Driver
     *
     * @return Next\DB\Table\RowSet
     *  RowSet Object with fetched data, if any
     *
     * @see Next\DB\Statement\Statement::fetch()
     */
    public function fetch( $fetchStyle = NULL ) {
        return new RowSet( $this, array( $this -> execute() -> fetch( $fetchStyle ) ) );
    }

    /**
     * Return an array containing all of the result set rows
     *
     * @param string|integer|optional $fetchStyle
     *  The Fetch Mode, accordingly to chosen Driver
     *
     * @return Next\DB\Table\Row|Next\DB\Table\RowSet
     *  RowSet Object with fetched data, if any
     *
     * @see Next\DB\Statement\Statement::fetchAll()
     */
    public function fetchAll( $fetchStyle = NULL ) {
        return new RowSet( $this, $this -> execute() -> fetchAll( $fetchStyle ) );
    }

    // CRUD-related Methods

    /**
     * Select Records from Table
     *
     * @param string|array|optional $columns
     *  Columns to be included in SELECT Statement
     *
     * @return Next\DB\Table\Select
     *  Select Object
     */
    public function select( $columns = Query::WILDCARD ) {
        return $this -> builder -> select( $columns );
    }

    /**
     * Insert a new Record in Table
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
     *  The ID of last record inserted
     *
     * @throws Next\DB\Table\TableException
     *  Trying to insert something without define any field
     */
    public function insert( $name = NULL ) {

        // Checking Integrity

        if( count( $this -> source ) == 0 ) {
            throw TableException::nothingToInsert();
        }

        $this -> builder -> insert( $this -> table -> getTable(), $this -> source );

        // Executing and returning the Last Insert ID...

        $this -> execute();

        return $this -> driver -> getConnection() -> lastInsertId( $name );
    }

    /**
     * Update Records in Table
     *
     * @return Next\DB\Table\Manager
     *  Manager instance, in order to allow method chaining to build the final query
     *
     * @throws Next\DB\Table\TableException
     *  Trying to execute an UPDATE Statement without define any field
     */
    public function update() {

        // Checking Integrity

        if( count( $this -> source ) == 0 ) {
            throw TableException::nothingToUpdate();
        }

        $this -> builder -> update( $this -> table -> getTable(), $this -> source );

        // Registering Placeholders Replacements

        $this -> addReplacements( $this -> source );

        return $this;
    }

    /**
     * Delete Records from Table
     *
     * @return Next\DB\Table\Manager
     *  Manager instance, in order to allow method chaining to build the final query
     */
    public function delete() {

        $this -> builder -> delete( $this -> table -> getTable() );

        return $this;
    }

    // Accessors

    /**
     * Get associated Table Object
     *
     * @return Next\DB\Table\Table
     *  Table Object
     */
    public function getTable() {
        return $this -> table;
    }

    /**
     * Get Data Source
     *
     * @return array
     *  Data Source
     */
    public function getSource() {
        return $this -> source;
    }

    /**
     * Get associated Connection Driver
     *
     * @return Next\DB\Driver\Driver
     *  Connection Driver
     */
    public function getDriver() {
        return $this -> driver;
    }

    // Auxiliary Methods

    /**
     * Wrapper method for Next\DB\Driver\Driver::prepare() and Next\DB\Statement\Statement:execute()
     *
     * @return Next\DB\Statement\Statement
     *  Statement Object
     *
     * @throws Next\DB\Table\TableException
     *  SQL Statement is empty
     *
     * @throws Next\DB\Table\TableException
     *  A DriverException or a StatementException is caught
     */
    private function execute() {

        $query = $this -> assemble();

        if( empty( $query ) ) {
            throw TableException::logic( 'Query is empty' );
        }

        // Preparing...

        try {

            $stmt = $this -> driver -> prepare( $query );

        } catch( DriverException $e ) {

            throw TableException::prepare( $e );
        }

        // ... and Executing

        try {

            $stmt -> execute( $this -> getReplacements() );

        } catch( StatementException $e ) {

            throw TableException::execute( $e );
        }

        return $stmt;
    }
}