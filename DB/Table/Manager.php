<?php

/**
 * Database Table Manager | DB\Table\Manager.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\Table;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\LengthException;

use Next\DB\Query\Query;            # Query Interface
use Next\DB\Driver\Driver;          # Connection Driver Interface

use Next\Components\Object;         # Object Class
use Next\Components\Invoker;        # Invoker Class
use Next\DB\Query\Builder;          # Query Builder Class
use Next\DB\Entity\Repositories;    # Repositories' Collection Class
use Next\DB\Table\RowSet;           # RowSet Class

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
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'driver' => [ 'type' => 'Next\DB\Driver\Driver', 'required' => TRUE ],
        'table'  => [ 'type' => 'Next\DB\Table\Table',   'required' => FALSE, 'default' => NULL ]
    ];

    /**
     * Query Builder
     *
     * @var \Next\DB\Query\Builder $builder
     */
    private $builder;

    /**
     * Entities Repositories
     *
     * @var \Next\DB\Entity\Repositories
     */
    private $repositories;

    /**
     * Data-source
     *
     * An associative array where the keys are Table Columns and
     * their associated values
     *
     * @var array $source
     */
    private $source = NULL;

    /**
     * Additional Initialization.
     * Prepares initial data-source, initializes Repositories Collection
     * and the Query Builder extending Manager's Context to it
     */
    protected function init() {

        $this -> repositories = new Repositories;

        /**
         * @internal Data-source
         *
         * By default Table Manager will work with original Table Fields.
         *
         * When a UPDATE Statement is executed, however, the Manager will
         * work with RowSet Fields computed from the difference between
         * original fields and modified fields
         */
        if( ! is_null( $this -> options -> table ) ) {
            $this -> source = array_filter( $this -> options -> table -> getFields() );
        }

        $this -> builder = new Builder(
            [ 'manager' => $this, 'renderer' => $this -> options -> driver -> getRenderer() ]
        );

        // Extend Object Context to QueryBuilder Class

        $this -> extend( new Invoker( $this, $this -> builder ) );
    }

    /**
     * Clean-up available Repositories when Table Manager is cloned
     */
    public function __clone() {
        $this -> repositories = new Repositories;
    }

    /**
     * Set Data-source
     *
     * @param array $source
     *  Data-source
     *
     * @return \Next\DB\Table\Manager
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
     * @see \Next\DB\Driver\Driver::lastInsertId()
     */
    public function rowCount() {

        $rowCount = $this -> execute() -> rowCount();

        $this -> flush();

        return $rowCount;
    }

    /**
     * Fetch the next row from a result set
     *
     * This method is a mere formality since records shouldn't
     * be used outside a RowSet
     *
     * @param string|integer|optional $fetchStyle
     *  The Fetch Mode, accordingly to chosen Driver
     *
     * @return \Next\DB\Table\RowSet
     *  RowSet Object with fetched data, if any
     *
     * @see \Next\DB\Statement\Statement::fetch()
     */
    public function fetch( $fetchStyle = NULL ) {

        $data = $this -> execute() -> fetch( $fetchStyle, array_slice( func_get_args(), 1 ) );

        $rowset = new RowSet( [ 'manager' => $this, 'source' => ( $data !== FALSE ? [ $data ] : [] ) ] );

        $this -> flush();

        return $rowset;
    }

    /**
     * Return an array containing all of the result set rows
     *
     * @param string|integer|optional $style
     *  The Fetch Mode, accordingly to chosen Driver
     *  Not directly used (documentation only)
     *
     * @return \Next\DB\Table\RowSet
     *  RowSet Object with fetched data, if any
     *
     * @see \Next\DB\Statement\Statement::fetchAll()
     */
    public function fetchAll( $style = NULL ) {

        $data = $this -> execute() -> fetchAll( func_get_args() );

        $rowset = new RowSet( [ 'manager' => $this, 'source' => ( $data !== FALSE ? $data : [] ) ] );

        $this -> flush();

        return $rowset;
    }

    // CRUD-related Methods

    /**
     * Select Records from Table
     *
     * @param string|array|optional $columns
     *  Columns to be included in SELECT Statement
     *
     * @return \Next\DB\Table\Select
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
     * @throws \Next\Exception\Exceptions\LengthException
     *  Trying to insert something without define any field
     */
    public function insert( $name = NULL ) {

        if( count( $this -> source ) == 0 ) {
            throw new LengthException( 'Nothing to insert' );
        }

        $this -> builder -> insert( $this -> options -> table -> getTableName(), $this -> source );

        // Executing and returning the Last Insert ID...

        $this -> execute();

        return $this -> options -> driver -> getConnection() -> lastInsertId( $name );
    }

    /**
     * Update Records in Table
     *
     * @return \Next\DB\Table\Manager
     *  Manager instance, in order to allow method chaining to build the final query
     *
     * @throws \Next\Exception\Exceptions\LengthException
     *  Trying to execute an UPDATE Statement without define any field
     */
    public function update() {

        if( count( $this -> source ) == 0 ) {
            throw new LengthException( 'Nothing to update' );
        }

        $this -> builder -> update( $this -> options -> table -> getTableName(), $this -> source );

        // Registering Placeholders Replacements

        $this -> addReplacements( $this -> source );

        return $this;
    }

    /**
     * Delete Records from Table
     *
     * @return \Next\DB\Table\Manager
     *  Manager instance, in order to allow method chaining to build the final query
     */
    public function delete() {

        $this -> builder -> delete( $this -> options -> table -> getTableName() );

        return $this;
    }

    /**
     * Resets the Table Manager by returning a new instance of it
     *
     * @return \Next\DB\Table\Manager
     *  Table Manager Object (Fluent-Interface)
     */
    public function reset() {

        return new Manager(
            [ 'driver' => $this -> options -> driver, 'table' => $this -> options -> table ]
        );
    }

    /**
     * Flushes Table Manager previously used informations, preparing it for another round
     *
     * @return \Next\DB\Table\Manager
     *  Table Manager Object (Fluent-Interface)
     */
    public function flush() {

        // Reset Query Builder Parts

        $this -> builder -> reset();

        return $this;
    }

    /**
     * Set Table Object after the Manager Object is built
     *
     * @param \Next\DB\Table\Table $table
     *  Table Object to replace the old one, if any
     *
     * @return  \Next\DB\Table\Manager
     *  Table Manager Object (Fluent-Interface)
     */
    public function setTable( Table $table ) {

        $this -> options -> table = $table;

        $this -> source = array_filter( $table -> getFields() );

        // Flushing any possible assembled query

        $this -> flush();

        return $this;
    }

    // Entity Repositories-related Methods

    /**
     * Add an Entity Repository
     *
     * @param string $repository
     *  Entity Repository to add
     *
     * @param string|optional $alias
     *  An optional alias for the Repository
     *
     * @return  \Next\DB\Table\Manager
     *  Table Manager Object (Fluent-Interface)
     */
    public function addRepository( $repository, $alias = NULL ) {

        $this -> repositories -> addRepository(
            $repository, $alias, new Manager( [ 'driver' => $this -> options -> driver ] )
        );

        return $this;
    }

    /**
     * Get an Entity Repository
     *
     * @param string $repository
     *  Entity Repository to retrieve
     *
     * @return \Next\DB\Entity\Repository
     *  Entity Repository
     *
     * @throws \Next\DB|Entity\EntityException
     *  Thrown if Repository Object doesn't exist
     */
    public function getRepository( $repository ) {
        return $this -> repositories -> getRepository( $repository );
    }

    /**
     * Get Entities Repository Collection
     *
     * @return \Next\DB\Entity\Repositories
     *  Entities Repositories
     */
    public function getRepositories() {
        return $this -> repositories;
    }

    // Accessors

    /**
     * Get assembled query
     *
     * @return string
     *  Assembled query
     */
    public function getQuery() {
        return $this -> assemble();
    }

    /**
     * Get associated Table Object
     *
     * @return \Next\DB\Table\Table
     *  Table Object
     */
    public function getTable() {
        return $this -> options -> table;
    }

    /**
     * Get Data-source
     *
     * @return array
     *  Data-source
     */
    public function getSource() {
        return $this -> source;
    }

    /**
     * Get associated Connection Driver
     *
     * @return \Next\DB\Driver\Driver
     *  Connection Driver
     */
    public function getDriver() {
        return $this -> options -> driver;
    }

    /**
     * Get Query Builder
     *
     * @return \Next\DB\Query\Builder
     *  Query Builder
     */
    public function getBuilder() {
        return $this -> builder;
    }

    // Auxiliary Methods

    /**
     * Wrapper method for \Next\DB\Driver\Driver::prepare() and Next\DB\Statement\Statement:execute()
     *
     * @return \Next\DB\Statement\Statement
     *  Statement Object
     *
     * @throws \Next\Exception\Exceptions\LengthException
     *  SQL Statement is empty
     *
     * @throws \Next\DB\Table\TableException
     *  A DriverException is caught
     */
    private function execute() {

        $query = $this -> assemble();

        if( empty( $query ) ) {
            throw new LengthException( 'Query is empty' );
        }

        // Preparing & Executing

        $stmt = $this -> options -> driver -> prepare( $query );

        $stmt -> execute( $this -> getReplacements() );

        return $stmt;
    }
}