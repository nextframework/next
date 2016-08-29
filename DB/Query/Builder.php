<?php

namespace Next\DB\Query;

use Next\Components\Object;             # Object Class
use Next\Components\Invoker;            # Invoker Class

use Next\DB\Query\Renderer\Renderer;    # Query Renderer

use Next\DB\Table\Manager;                # Table Manager Object

class Builder extends Object {

    /**
     * Query Renderer
     *
     * @var Next\DB\Query\Renderer\Renderer
     */
    private $renderer;

    /**
     * Table Manager Object
     * Injected through Extended Context from Next\DB\Table\Manager::__construct()
     *
     * @var Next\DB\Table\Manager
     *
     * @see Next\DB\Table\Manager::__construct()
     * @see Next\Components\Context::extend()
     */
    protected $_manager;

    /**
     * Built Query
     *
     * @var string $query
     */
    private $query;

    /**
     * Query Placeholders Replacements
     *
     * @var array $replacements
     */
    private $replacements = array();

    /**
     * Columns to be include in SELECT Statement
     *
     * @var array $columns
     */
    private $columns = array();

    /**
     * Tables to be searched
     *
     * @var array $tables
     */
    private $tables = array();

    /**
     * DISTINCT Flag
     *
     * @var boolean $distinct
     */
    private $distinct = FALSE;

    /**
     * WHERE Clauses
     *
     * @var array $where
     */
    private $where = array();

    /**
     * JOIN Clauses
     *
     * @var array $joins
     */
    private $joins = array();

    /**
     * HAVING Clauses
     *
     * @var array $having
     */
    private $having = array();

    /**
     * GROUP BY Fields
     *
     * @var string $group
     */
    private $group;

    /**
     * ORDER BY Fields
     *
     * @var array $order
     */
    private $order = array();

    /**
     * LIMIT Clause
     *
     * @var array $limit
     */
    private $limit = array();

    /**
     * Table Select Constructor
     *
     * @param Next\DB\Query\Renderer $renderer
     *  Query Renderer to be used
     */
    public function __construct( Renderer $renderer ) {

        parent::__construct();

        $this -> renderer = $renderer;
        //$this -> manager  = $manager;
    }

    // CRUD-related methods

    /**
     * Build an INSERT Statement
     *
     * @param string $table
     *  Table name
     *
     * @param array $fields
     *  Columns to be added in INSERT Statement
     *
     * @return Next\DB\Table\Select
     *  Table Select Object (Fluent Interface)
     */
    public function insert( $table, array $fields ) {

        $this -> query = $this -> renderer -> insert( $table, array_keys( $fields ) );

        $this -> addReplacements( $fields );

        return $this;
    }

    /**
     * Render UPDATE Statement
     *
     * @param string $table
     *  Table name
     *
     * @param array $fields
     *  Columns to be added in UPDATE Statement
     *
     * @return Next\DB\Table\Select
     *  Table Select Object (Fluent Interface)
     */
    public function update( $table, array $fields ) {

        $this -> query = $this -> renderer -> update( $table, array_keys( $fields ) );

        $this -> addReplacements( $fields );

        return $this;
    }

    /**
     * Build a DELETE Statement
     *
     * @param string $table
     *  Table Name
     *
     * @return Next\DB\Table\Select
     *  Table Select Object (Fluent Interface)
     */
    public function delete( $table ) {

        $this -> query = $this -> renderer -> delete( $table );

        return $this;
    }

    // Select-related Rendering Methods

    /**
     * Specify SELECT Statement Columns
     *
     * @param Next\DB\Query\Expression|string|array|optional $columns
     *  Columns to be included in SELECT Statement
     *
     * @return Next\DB\Table\Select
     *  Table Select Object (Fluent Interface)
     */
    public function select( $columns = array() ) {

        // Is the SELECT Column an single Expression?

        if( $columns instanceof Expression ) {

            $this -> columns = (array) $columns -> getExpression();

            return $this;
        }

        $columns = (array) $columns;

        /**
         * @internal
         *
         * Fixing common mistakes in method arguments:
         *
         * - array()                        <empty array>
         * - array( '*' )                   <single index array with a SQL wildcard
         * - ''                             <an empty string>
         * - array('')                      <single-index array with an empty string>
         * - array( 'something' => '' )     <single-index associative array with an empty string>
         * - array( 'something' => '*' )    <single-index associative array with a SQL wildcard>
         */
        $c   = $columns; // Need some shortening u.u'
        $cnt = count( $columns );

        if( $cnt == 0 || ( $cnt == 1 && ( reset( $c ) !== FALSE && ( $c[ key( $c ) ] == Query::WILDCARD || empty( $c[ key( $c ) ] ) ) ) ) ) {

            $this -> columns = (array) Query::WILDCARD;

            return $this;
        }

        /**
         * @internal
         *
         * More fixes. This time some cleanup:
         *
         * - array( '          somefield' )
         * - array( 'somefield', '', 'anotherfield' )
         *
         * Also, we'll get the Expression value, if any
         */
        $columns = array_filter(

            array_map(

                function( $column ) {
                    return ( $column instanceof Expression ? $column : trim( $column ) );
                },

                $columns
            )
        );

        /**
         * @internal
         *
         * Adding Primary Key Column to the list
         *
         * If we have a Next\DB\Table\Table defined and its Primary Key Column
         * has been filled defined, we'll search for its value in the column
         * list built so far
         *
         * We'll also search for a possible table alias name, usually
         * x.column, being 'x' the first letter of the model
         *
         * If none of these nor the Query Renderer Wildcard can be found,
         * we'll prepend a full column name to the list, using the same
         * rule used for searching
         */
        $table = $this -> _manager -> getTable();

        if( $table !== NULL ) {

            $primary = $table -> getPrimaryKey();

            $fullColumnName = sprintf(
                '%s.%s', substr( $table -> getTable(), 0, 1 ), $primary
            );

            if( array_search( Query::WILDCARD, $columns ) === FALSE ) {
                array_unshift( $columns, $fullColumnName );
            }
        }

        /**
         * Usage:
         *
         * <code>
         *  array( 'username' => 'long_and_complex_field_for_username' )
         * </code>
         *
         * Will become, after built the Statement:
         *
         * <code>
         *  SELECT `long_and_complex_field_for_username` AS `username`
         * </code>
         *
         * And your favorite Fetch Mode will use the Field Alias
         * as index (or property)
         */
        foreach( $columns as $alias => $column ) {
            $this -> columns[] = $this -> renderer -> columns( $column, $alias );
        }

        return $this;
    }

    /**
     * Specify the columns to be searched
     *
     * @param array|optional $tables
     *  One or more different tables to search
     *
     * @return Next\DB\Table\Select
     *  Table Select Object (Fluent Interface)
     */
    public function from( $tables = array() ) {

        $tablename = $this -> _manager -> getTable() -> getTable();

        /**
         * @internal
         *
         * If only one table was informed, as string, it's not possible
         * to defined an alias and because the PRIMARY KEY, if properly defined
         * in Next\DB\Table\Table, is automatically added, we need to enforce the
         * alias used in here
         *
         * The same rules apply: The alias will be first character of the
         * Next\DB\Table\Table
         */
        if( ! is_array( $tables ) ) {

            if( strpos( $this -> columns[ 0 ], '.' ) !== FALSE ) {

                $tables = array(
                    strtolower( substr( $tablename, 0, 1 ) ) => $tables
                );
            }
        }

        /**
         * Usage:
         *
         * <code>array( 'm' => 'members' )</code>
         *
         * Will become, after built the Statement:
         *
         * <code>SELECT fields FROM `members` m</code>
         *
         * If an alias is not defined as element index, it will not exist (obviously)
         */
        foreach( (array) $tables as $alias => $table ) {

            /**
             * @internal
             * If we have multiple tables listed we'll compare the table name
             * coming from Next\DB\Table\Table::getTablename(), against the
             * tables included for the statement
             *
             * If found without a string alias, we'll enforce it to match the
             * automatically added PRIMARY KEY
             *
             * The same rules apply: The alias will be first character of the
             * Next\DB\Table\Table
             */
            if( $pos = strpos( $table, $tablename ) !== FALSE ) {

                if( ! is_string( $alias ) ) {
                    $alias = strtolower( substr( $tablename, 0, 1 ) );
                }
            }

            $this -> tables[] = $this -> renderer -> from( $alias, $table );
        }

        /**
         * @internal
         * Assembling Query
         * From this point all SQL is a Clause
         */
        $this -> query = $this -> renderer -> select(
            $this -> columns, $this -> tables, $this -> distinct
        );

        return $this;
    }

    /**
     * Specify a DISTINCT Clause
     *
     * @return Next\DB\Table\Select
     *  Table Select Object (Fluent Interface)
     */
    public function distinct() {

        $this -> distinct = TRUE;

        return $this;
    }

    // Other Clauses

    /**
     * Add a WHERE Clause
     *
     * @param array|string $condition
     *  WHERE Clause
     *
     * NOTE: In order to implicit maintainability, only the first index will be used.
     * For multiple WHERE conditions, call the method again
     *
     * @param array|optional $values
     *  Value for Clause Placeholders, if any
     *
     * @param mixed|string|optional $type
     *  The WHERE Clause condition type, 'AND' or 'OR'.
     *
     *  Defaults to 'AND'
     *
     * @return Next\DB\Table\Select
     *  Table Select Object (Fluent Interface)
     */
    public function where( $condition, $values = array(), $type = Query::SQL_AND ) {

        $this -> where[ $type ][] = ( is_array( $condition ) ? array_shift( $condition ) : $condition );

        $this -> addReplacements( $values );

        return $this;
    }

    /**
     * Add a JOIN Clause
     *
     * @param string|array $table
     *  - A string with the JOIN Table
     *  - A single-index array with JOIN Table and its alias as key. E.g.:
     *
     *  <code>array( 'm' => 'members' )</code>
     *
     *  NOTE: In order to implicit maintainability, only the first index will be used.
     *  For multiple JOINS, call the method again
     *
     * @param string $on
     *  The ON Clause
     *
     * @param string|optional $type
     *  The JOIN Type
     *
     *  The Query interface has three values of JOIN Types:
     *  Query::INNER_JOIN, Query::LEFT_OUTER_JOIN and Query::RIGHT_OUTER_JOIN
     *
     *  However there are no constraints about what it's accepted here because there are numerous
     *  valid aliases for this value
     *
     * @return Next\DB\Table\Select
     *  Table Select Object (Fluent-Interface)
     */
    public function join( $table, $on, $type = Query::INNER_JOIN ) {

        $table = ( is_array( $table ) ? array_slice( $table, 0, 1 ) : $table );

        $this -> joins[] = $this -> renderer -> join( $table, $on, $type );

        return $this;
    }

    /**
     * Add a HAVING Clause
     *
     * @param array|string $condition
     *  HAVING Clause
     *
     * NOTE: In order to implicit maintainability, only the first index will be used.
     * For multiple HAVING conditions, call the method again
     *
     * @param array|optional $value
     *  Value for Clause Placeholders, if any
     *
     * @param mixed|string|optional $type
     *  The HAVING Clause condition type, 'AND' or 'OR'.
     *
     *  Defaults to 'AND'
     *
     * @return Next\DB\Table\Select
     *  Table Select Object (Fluent Interface)
     */
    public function having( $condition, $values = array(), $type = Query::SQL_AND ) {

        $this -> having[ $type ][] = ( is_array( $condition ) ? array_shift( $condition ) : $condition );

        $this -> addReplacements( $values );

        return $this;
    }

    /**
     * Add a GROUP BY Clause
     *
     * @param string|array $fields
     *  Fields to group results
     *
     * @return Next\DB\Table\Select
     *  Table Select Object (Fluent Interface)
     */
    public function group( $fields ) {

        $this -> group = array_filter( array_map( 'trim', (array) $fields ) );

        return $this;
    }

    /**
     * Add ORDER BY Clause(s)
     *
     * @param string|array $field
     *  - As a string, the field to order</p>
     *  - As an associative array, keys are the fields and values order types
     *
     * @param string|optional $type
     *  Orientation, if <strong>$field</strong> is not an array: ASC or DESC.
     *  Defaults to ASC
     *
     * @return Next\DB\Table\Select
     *  Table Select Object (Fluent Interface)
     */
    public function order( $field, $type = Query::ORDER_ASCENDING ) {

        $this -> order[] = ( is_array( $field ) ? $field : array( $field => $type ) );

        return $this;
    }

    /**
     * Add a LIMIT Clause, with or without an offset
     *
     * @param integer|optional $limit
     *  Number of records to be returned.
     *  Defaults to 1 and it'll be forced to be greater than zero
     *
     * @param integer|optional $offset
     *  Record offset to start.
     *  Defaults to 0 and it'll be forced to not be negative
     *
     * @return Next\DB\Table\Select
     *  Table Select Object (Fluent Interface)
     */
    public function limit( $limit = 1, $offset = 0 ) {

        $limit  = (int) $limit;
        $offset = (int) $offset;

        $this -> limit = array(

            ( $offset >= 0 ? $offset : 0 ),

            ( $limit > 0 ? $limit : 1 )
        );

        return $this;
    }

    /**
     * Assemble the Query
     *
     * @return string
     *  Built Query
     */
    public function assemble() {

        if( count( $this -> joins ) > 0 ) {
            $this -> query .= implode( '', $this -> joins );
        }

        if( count( $this -> where ) > 0 ) {
            $this -> query .= $this -> renderer -> where( $this -> where );
        }

        if( count( $this -> group ) > 0 ) {
            $this -> query .= $this -> renderer -> group( $this -> group );
        }

        if( count( $this -> having ) > 0 ) {
            $this -> query .= $this -> renderer -> having( $this -> having );
        }

        if( count( $this -> order ) > 0 ) {
            $this -> query .= $this -> renderer -> order( $this -> order );
        }

        if( count( $this -> limit ) > 0 ) {
            $this -> query .= $this -> renderer -> limit( $this -> limit );
        }

        return $this -> query;
    }

    // Accessors

    /**
     * Get built query
     *
     * @return string
     *  Built query
     */
    public function getQuery() {
        return $this -> query;
    }

    /**
     * Get placeholders replacements
     *
     * @return array
     *  Placeholders replacements
     */
    public function getReplacements() {
        return $this -> replacements;
    }

    // Auxiliary Method

    /**
     * Add Placeholders Replacements
     *
     * @param mixed|array $placeholders
     *  Query Placeholders Replacements
     *
     * @return Next\DB\Query\Builder
     *  Query Builder Instance (Fluent Interface)
     */
    public function addReplacements( $replacements ) {

        $this -> replacements = \Next\Components\Utils\ArrayUtils::union(
            $this -> replacements, (array) $replacements
        );

        return $this;
    }

    /**
     * Resets the Query Builder preparing it for another (possible) statement
     *
     * Although all the property types can be predefined while defining them, by having such feature
     * we can flush them after used, which doesn't occur within this class scope
     *
     * @return Next\Query\Builder
     *  Query Builder Object (Fluent-Interface)
     *
     * @see Next\DB\Table\Manager::flush()
     */
    public function reset() {

        $this -> query = NULL;
        $this -> replacements = array();

        $this -> columns = array();
        $this -> tables = array();

        $this -> distinct = FALSE;

        $this -> where = array();
        $this -> joins = array();
        $this -> having = array();

        $this -> group = NULL;
        $this -> order = array();
        $this -> limit = array();

        return $this;
    }
}