<?php

/**
 * Database Entity Manager | DB\Entity\Manager.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\Entity;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\LengthException;

use Next\DB\Query\Query;           # Query Interface
use Next\DB\Driver\Driver;         # Connection Driver Interface

use Next\Components\Object;        # Object Class
use Next\Components\Invoker;       # Invoker Class
use Next\DB\Entity\Entity;         # DB Entity Class
use Next\DB\Query\Builder;         # Query Builder Class
use Next\DB\DataGateway\RowSet;    # RowSet Class

/**
 * Entity Manager Class
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
        'driver'      => [ 'type' => 'Next\DB\Driver\Driver',     'required' => TRUE ],
        'repository'  => [ 'type' => 'Next\DB\Entity\Repository', 'required' => TRUE ]
    ];

    /**
     * Query Builder
     *
     * @var \Next\DB\Query\Builder $builder
     */
    private $builder;

    /**
     * Data-source
     *
     * An associative array where the keys are Entity Columns and
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

        /**
         * @internal Data-source
         *
         * By default the Entity Manager will work with original
         * Entity Fields
         *
         * When a UPDATE Statement is executed, however, the Manager
         * will work with RowSet Fields computed from the difference
         * between original fields and modified fields
         */
        $this -> source = array_filter(
            $this -> options -> repository -> getEntity() -> getFields()
        );

        $this -> builder = new Builder(
            [
                'renderer' => $this -> options -> driver -> getRenderer(),
                'table'    => $this -> options -> repository -> getEntity() -> getEntityName()
            ]
        );

        // Extend Manager's Context to QueryBuilder Class

        $this -> extend( new Invoker( $this, $this -> builder ) )
              -> extend( new Invoker( $this, $this -> options -> repository ) );

        // Extending Repository's Context to Manager's and Query Builder Classes

        $this -> options
              -> repository
              -> extend( new Invoker( $this -> options -> repository, $this ) )
              -> extend( new Invoker( $this -> options -> repository, $this -> builder ) );
    }

    /**
     * Set Data-source
     *
     * @param array $source
     *  Data-source
     *
     * @return \Next\DB\Entity\Manager
     *  Entity Manager Object (Fluent Interface)
     */
    public function setSource( array $source ) {

        $this -> source = $source;

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
     * @return \Next\DB\DataGateway\RowSet
     *  RowSet Object with fetched data, if any
     *
     * @see \Next\DB\Statement\Statement::fetch()
     */
    public function fetch( $fetchStyle = NULL ) {

        $data = $this -> execute() -> fetch( $fetchStyle, array_slice( func_get_args(), 1 ) );

        $rowset = new RowSet(
            [
                'manager' => $this,
                'source' => ( $data !== FALSE ? [ $data ] : [] )
            ]
        );

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
     * @return \Next\DB\DataGateway\RowSet
     *  RowSet Object with fetched data, if any
     *
     * @see \Next\DB\Statement\Statement::fetchAll()
     */
    public function fetchAll( $style = NULL ) {

        $data = $this -> execute() -> fetchAll( func_get_args() );

        $rowset = new RowSet(
            [
                'manager' => $this,
                'source' => ( $data !== FALSE ? $data : [] )
            ]
        );

        $this -> flush();

        return $rowset;
    }

    // CRUD-related Methods

    /**
     * Selects records from Database Table associated to provided Entity
     *
     * @param string|array|optional $columns
     *  Columns to be included in SELECT Statement
     *
     * @return \Next\DB\Query\Builder
     *  Query Builder Object (Fluent Interface)
     */
    public function select( $columns = Query::WILDCARD ) {
        return $this -> builder -> select( $columns );
    }

    /**
     * Inserts a new record on Database Table associated with
     * provided Entity
     *
     * @param string|optional $name
     *  Name of the sequence object from which the ID should be returned
     *  According to PHP Manual it's used, for example, by PDO_PGSQL
     *  as sequence object identifier
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

        $this -> builder -> insert(

            $this -> options -> repository -> getEntity() -> getEntityName(),

            $this -> source
        );

        // Executing and returning the Last Insert ID...

        $this -> execute();

        return $this -> options -> driver -> getConnection() -> lastInsertId( $name );
    }

    /**
     * Updates records on Database Table associated with
     * provided Entity
     *
     * @return \Next\DB\Entity\Manager
     *  Manager instance, in order to allow method chaining to build the final query
     *
     * @throws \Next\Exception\Exceptions\LengthException
     *  Trying to execute an UPDATE Statement without define any field
     */
    public function update() {

        if( count( $this -> source ) == 0 ) {
            throw new LengthException( 'Nothing to update' );
        }

        $this -> builder -> update(

            $this -> options -> repository -> getEntity() -> getEntityName(),

            $this -> source
        );

        // Registering Placeholders Replacements

        $this -> addReplacements( $this -> source );

        return $this;
    }

    /**
     * Delete records from Database Table associated with
     * provided Entity
     *
     * @return \Next\DB\Entity\Manager
     *  Manager instance, in order to allow method chaining to build the final query
     */
    public function delete() {

        $this -> builder -> delete(
            $this -> options -> repository -> getEntity() -> getEntityName()
        );

        return $this;
    }

    /**
     * Resets the Entity Manager by returning a new instance of it
     *
     * @return \Next\DB\Entity\Manager
     *  Entity Manager Object (Fluent-Interface)
     */
    public function reset() {

        return new Manager(
            [
                'driver'      => $this -> options -> driver,
                'repository'  => $this -> options -> repository
            ]
        );
    }

    /**
     * Flushes Entity Manager's previously used informations,
     * preparing it for a new use
     *
     * @return \Next\DB\Entity\Manager
     *  Entity Manager Object (Fluent-Interface)
     */
    public function flush() {

        // Reset Query Builder Parts

        $this -> builder -> reset();

        return $this;
    }

    /**
     * Sets a new Entity Object after the Manager Object is built
     *
     * @param \Next\DB\Entity\Entity $entity
     *  Entity Object to replace the old one, if any
     *
     * @return  \Next\DB\Entity\Manager
     *  Entity Manager Object (Fluent-Interface)
     */
    public function setEntity( Entity $entity ) {

        $this -> source = array_filter( $entity -> getFields() );

        // Flushing any possible assembled query

        $this -> flush();

        return $this;
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
     * Get associated Entity Object
     *
     * @return \Next\DB\Entity\Entity
     *  Entity Object
     */
    public function getEntity() {
        return $this -> options -> repository -> getEntity();
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
     * Wrapper method for \Next\DB\Driver\Driver::prepare()
     * and Next\DB\Statement\Statement:execute()
     *
     * @return \Next\DB\Statement\Statement
     *  Statement Object
     *
     * @throws \Next\Exception\Exceptions\LengthException
     *  SQL Statement is empty
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