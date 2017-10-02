<?php

/**
 * Database Query Builder Class | DB\Query\Builder.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\Query;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\BadMethodCallException;
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Object;              # Object Class
use Next\Components\Invoker;             # Invoker Class

use Next\Components\Utils\ArrayUtils;    # Array Utils Class

use Next\DB\Table\Manager;               # Table Manager Object

/**
 * Defines the Query Builder Object responsible to assemble a
 * Database Query through class' methods
 *
 * @package    Next\DB\Query
 */
class Builder extends Object {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'manager' => [ 'type' => 'Next\DB\Table\Manager', 'required' => TRUE ],
        'renderer' => [ 'type' => 'Next\DB\Query\Renderer\Renderer', 'required' => TRUE ]
    ];

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
    private $replacements = [];

    /**
     * Columns to be include in SELECT Statement
     *
     * @var array $columns
     */
    private $columns = [];

    /**
     * Tables to be searched
     *
     * @var array $tables
     */
    private $tables = [];

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
    private $where = [];

    /**
     * JOIN Clauses
     *
     * @var array $joins
     */
    private $joins = [];

    /**
     * HAVING Clauses
     *
     * @var array $having
     */
    private $having = [];

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
    private $order = [];

    /**
     * LIMIT Clause
     *
     * @var array $limit
     */
    private $limit = [];

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
     * @return \Next\DB\Table\Select
     *  Table Select Object (Fluent Interface)
     */
    public function insert( $table, array $fields ) {

        $this -> query = $this -> options
                               -> renderer
                               -> insert(
                                    $table, array_keys( $fields )
                                );

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
     * @return \Next\DB\Table\Select
     *  Table Select Object (Fluent Interface)
     */
    public function update( $table, array $fields ) {

        $this -> query = $this -> options
                               -> renderer
                               -> update(
                                    $table, array_keys( $fields )
                                );

        $this -> addReplacements( $fields );

        return $this;
    }

    /**
     * Build a DELETE Statement
     *
     * @param string $table
     *  Table Name
     *
     * @return \Next\DB\Table\Select
     *  Table Select Object (Fluent Interface)
     */
    public function delete( $table ) {

        $this -> query = $this -> options
                               -> renderer
                               -> delete( $table );

        return $this;
    }

    // Select-related Rendering Methods

    /**
     * Specify SELECT Statement Columns
     *
     * @param \Next\DB\Query\Expression|string|array|optional $columns
     *  Columns to be included in SELECT Statement
     *
     * @return \Next\DB\Table\Select
     *  Table Select Object (Fluent Interface)
     */
    public function select( $columns = [] ) {

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
         * - []                        <empty array>
         * - [ '*' ]                   <single index array with a SQL wildcard
         * - ''                        <an empty string>
         * - [ '' ]                    <single-index array with an empty string>
         * - [ 'something' => '' ]     <single-index associative array with an empty string>
         * - [ 'something' => '*' ]    <single-index associative array with a SQL wildcard>
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
         * - [ '          somefield' ]
         * - [ 'somefield', '', 'anotherfield' ]
         *
         * We'll also get the Expression value, if any
         */
        $columns = array_filter(

            array_map(

                function( $column ) {

                    return ( $column instanceof Expression ?
                        $column : trim( $column ) );
                },

                $columns
            )
        );

        /**
         * Usage:
         *
         * ````
         *  [ 'username' => 'long_and_complex_field_for_username' ]
         * ````
         *
         * Will become, after built the Statement:
         *
         * ````
         *  SELECT `long_and_complex_field_for_username` AS `username`
         * ````
         *
         * And your favourite Fetch Mode will use the Field Alias
         * as index (or property)
         */
        foreach( $columns as $alias => $column ) {
            $this -> columns[] = $this -> options
                                       -> renderer
                                       -> columns( $column, $alias );
        }

        return $this;
    }

    /**
     * Specify the columns to be searched
     *
     * @param array|optional $tables
     *  One or more different tables to search
     *
     * @return \Next\DB\Table\Select
     *  Table Select Object (Fluent Interface)
     */
    public function from( $tables = [] ) {

        $tablename = $this -> options
                           -> manager -> getTable() -> getTableName();

        /**
         * @internal
         *
         * If only one table was informed, as string, it's not possible
         * to defined an alias and because the PRIMARY KEY, if properly defined
         * in \Next\DB\Table\Table, is automatically added, we need to enforce the
         * alias used in here
         *
         * The same rules apply: The alias will be first character of the
         * \Next\DB\Table\Table
         */
        if( ! is_array( $tables ) ) {

            if( strpos( $this -> columns[ 0 ], '.' ) !== FALSE ) {
                $tables = [ strtolower( substr( $tablename, 0, 1 ) ) => $tables ];
            }
        }

        /**
         * Usage:
         *
         * ````[ 'm' => 'members' ]````
         *
         * Will become, after built the Statement:
         *
         * ````SELECT fields FROM `members` m````
         *
         * If an alias is not defined as element index, it will not exist (obviously)
         */
        foreach( (array) $tables as $alias => $table ) {

            /**
             * @internal
             * If we have multiple tables listed we'll compare the table name
             * coming from \Next\DB\Table\Table::getTablename(), against the
             * tables included for the statement
             *
             * If found without a string alias, we'll enforce it to match the
             * automatically added PRIMARY KEY
             *
             * The same rules apply: The alias will be first character of the
             * \Next\DB\Table\Table
             */
            if( $pos = strpos( $table, $tablename ) !== FALSE ) {

                if( ! is_string( $alias ) ) {
                    $alias = strtolower( substr( $tablename, 0, 1 ) );
                }
            }

            $this -> tables[] = $this -> options
                                      -> renderer
                                      -> from( $alias, $table );
        }

        /**
         * @internal
         * Assembling Query
         * From this point all SQL is a Clause
         */
        $this -> query = $this -> options -> renderer -> select(
            $this -> columns, $this -> tables, $this -> distinct
        );

        return $this;
    }

    /**
     * Specify a DISTINCT Clause
     *
     * @return \Next\DB\Table\Select
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
     * @param array|mixed|optional $replacements
     *  Value for Clause Placeholders, if any
     *
     *  If `$condition` has multiple possible values, `$replacements` must
     *  be an associative array led by that field name. E.g.:
     *
     *  ````
     *  Array
     *  (
     *      [field] => Array
     *          (
     *              [0] => value_1
     *              [1] => value_2
     *              [2] => value_3
     *          )
     *  )
     *  ````
     *
     * This helps not having to manually code a loop calling Build::where() for each
     * one of them with potentially insecure workarounds for the multiplicity
     *
     * @param mixed|string|optional $type
     *  The WHERE Clause condition type, 'AND' or 'OR'.
     *
     *  Defaults to 'AND'
     *
     * @return \Next\DB\Table\Select
     *  Table Select Object (Fluent Interface)
     *
     * @throws QueryException
     *  Thrown if trying to use multiple conditions at once in order to
     *  implicit maintainability as for multiple WHERE conditions,
     *  Builder::where() should be called again
     *
     * @throws \Next\Exception\Exceptions\BadMethodCallException
     *  Thrown if trying to create a multiple possibilities condition
     *  by defining all of them, repeatedly, as an array,
     *  enforcing maintainability
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if when creating a multiple possibilities condition,
     *  the first entry on the array of replacements doesn't have a
     *  key or its key doesn't match any CLAUSE define on `$condition`
     */
    public function where( $condition, $replacements = [], $type = Query::SQL_AND ) {

        if( is_array( $condition ) ) {

            throw new BadMethodCallException(
                'Multiple conditions are not allowed (Builder::where'
            );
        }

        /**
         * @internal
         *
         * Most of the times `$replacements` will be an array in the
         * format `[ 'field' => 'value' ]` being `field ` a matching
         * column used on `$condition` so the checking is made over
         * the number of replacements
         *
         * Anything greater than 1 (one) theoretically characterizes a
         * multiple possibilities condition
         */
        if( count( $replacements, COUNT_RECURSIVE ) > 1 ) {

            // Only the first array will be used as multiple replacements values list

            $key = (array) array_keys( $replacements );
            $key = array_shift( $key );

            if( is_null( $key ) ) {

                throw new InvalidArgumentException(

                    'Multiple possibilities conditions expects all of
                    them to be under an associative array with a
                    valid key'
                );
            }

            if( strpos( $condition, $key ) === FALSE ) {

                throw new InvalidArgumentException(

                    'Multiple possibilities conditions expects the
                    condition to be defined as leading key on
                    replacements\' list'
                );
            }

            foreach( $replacements[ $key ] as $value ) {

                // Generating a unique ID for each field occurrence

                $uniqid = bin2hex( openssl_random_pseudo_bytes( 12 ) );

                // Replacing `$key` occurrences found in `$condition`

                $this -> where[ $type ][] = str_replace(

                    sprintf( ':%s', $key ),

                    sprintf( ':%s_%s', $key, $uniqid ), $condition
                );

                // Adding a modified version of Replacements List for Query Renderer

                $this -> addReplacements(
                    [ sprintf( '%s_%s', $key, $uniqid ) => $value ]
                );
            }

        } else {

            // Regular 1:1 conditions

            $this -> where[ $type ][] = $condition;

            $this -> addReplacements( $replacements );
        }

        return $this;
    }

    /**
     * Add a JOIN Clause
     *
     * @param string|array $table
     *  - A string with the JOIN Table
     *  - A single-index array with JOIN Table and its alias as key. E.g.:
     *
     *  ````[ 'm' => 'members' ]````
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
     * @return \Next\DB\Table\Select
     *  Table Select Object (Fluent-Interface)
     */
    public function join( $table, $on, $type = Query::INNER_JOIN ) {

        $table = ( is_array( $table ) ?
                    array_slice( $table, 0, 1 ) : $table );

        $this -> joins[] = $this -> options
                                 -> renderer
                                 -> join( $table, $on, $type );

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
     * @param array|optional $values
     *  Values for Clause Placeholders, if any
     *
     * @param mixed|string|optional $type
     *  The HAVING Clause condition type, 'AND' or 'OR'.
     *
     *  Defaults to 'AND'
     *
     * @return \Next\DB\Table\Select
     *  Table Select Object (Fluent Interface)
     */
    public function having( $condition, $values = [], $type = Query::SQL_AND ) {

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
     * @return \Next\DB\Table\Select
     *  Table Select Object (Fluent Interface)
     */
    public function group( $fields ) {

        $this -> group = array_filter(
            array_map( 'trim', (array) $fields )
        );

        return $this;
    }

    /**
     * Add ORDER BY Clause(s)
     *
     * @param string|array|Next\DB\Query\Expression $field
     *  - As a string, the field to order
     *  - As an associative array, keys are the fields and values
     *    order types
     *  - As a Next\DB\Query\Expression, the Object itself to be
     *    rendered "as is"
     *
     * @param string|optional $type
     *  Orientation, if <strong>$field</strong> is not an array: ASC or DESC.
     *  Defaults to ASC
     *
     * @return \Next\DB\Table\Select
     *  Table Select Object (Fluent Interface)
     */
    public function order( $field, $type = Query::ORDER_ASCENDING ) {

        if( $field instanceof Expression ) {

            $this -> order[] = $field;

            return $this;
        }

        $this -> order[] = ( is_array( $field ) ? $field : [ $field => $type ] );

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
     * @return \Next\DB\Table\Select
     *  Table Select Object (Fluent Interface)
     */
    public function limit( $limit = 1, $offset = 0 ) {

        $limit  = (int) $limit;
        $offset = (int) $offset;

        $this -> limit = [

            ( $offset >= 0 ? $offset : 0 ),

            ( $limit > 0 ? $limit : 1 )
        ];

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

            $this -> query .= $this -> options
                                    -> renderer
                                    -> where( $this -> where );
        }

        if( count( $this -> group ) > 0 ) {

            $this -> query .= $this -> options
                                    -> renderer
                                    -> group( $this -> group );
        }

        if( count( $this -> having ) > 0 ) {

            $this -> query .= $this -> options
                                    -> renderer
                                    -> having( $this -> having );
        }

        if( count( $this -> order ) > 0 ) {

            $this -> query .= $this -> options
                                    -> renderer
                                    -> order( $this -> order );
        }

        if( count( $this -> limit ) > 0 ) {

            $this -> query .= $this -> options
                                    -> renderer
                                    -> limit( $this -> limit );
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
     * @param mixed|array $replacements
     *  Query Placeholders Replacements
     *
     * @return \Next\DB\Query\Builder
     *  Query Builder Instance (Fluent Interface)
     */
    public function addReplacements( $replacements ) {

        $this -> replacements = ArrayUtils::union(
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
     * @return \Next\Query\Builder
     *  Query Builder Object (Fluent-Interface)
     *
     * @see \Next\DB\Table\Manager::flush()
     */
    public function reset() {

        $this -> query        = NULL;
        $this -> replacements = [];

        $this -> columns      = [];
        $this -> tables       = [];

        $this -> distinct     = FALSE;

        $this -> where        = [];
        $this -> joins        = [];
        $this -> having       = [];

        $this -> group        = NULL;
        $this -> order        = [];
        $this -> limit        = [];

        return $this;
    }

    /**
     * Get Entity Manager
     *
     * @return \Next\DB\Table\Manager
     *  Entity Manager
     */
    public function getManager() {
        return $this -> options -> manager;
    }
}