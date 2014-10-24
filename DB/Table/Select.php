<?php

namespace Next\DB\Table;

use Next\Components\Object;             # Object Class
use Next\Components\Invoker;            # Invoker Class

use Next\DB\Query\Query;                # Query Interface
use Next\DB\Query\Renderer\Renderer;    # Query Renderer
use Next\DB\Query\Expression;           # Expression Class
use Next\DB\Query\Builder;              # Query Builder Class

/**
 * Table Select Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Select extends Object {

    /**
     * Columns to be include in SELECT Statement
     *
     * @var string $columns
     */
    private $columns;

    /**
     * Tables to be searched
     *
     * @var string $tables
     */
    private $tables;

    private $joins = array();

    /**
     * Select Constructor
     *
     * Automatically set SELECT Statement Columns
     *
     * @param Next\DB\Renderer\Renderer $renderer
     *   Query Renderer
     *
     * @param string|array|optional $columns
     *   Columns to be included in SELECT Statement
     */
    public function __construct( Renderer $renderer, $columns = Query::SQL_WILDCARD ) {

        parent::__construct();

        // Extend Object Context to QueryBuilder Class

        $this -> extend( new Invoker( $this, new Builder( $renderer ) ) );

        /**
         * Initializing Structure
         *
         * @see Next\DB\Table\Select::reset()
         */
        $this -> columns   = NULL;
        $this -> tables    = NULL;

        // Starting SELECT Statement

        $this -> select( $columns );
    }

    /**
     * Setup SELECT Statement Columns
     *
     * @param string|array|optional $columns
     *   Columns to be included in SELECT Statement
     *
     * @return Next\DB\Table\Select
     *   Table Select Object (Fluent Interface)
     */
    public function select( $columns = array() ) {

        $renderer = $this -> getRenderer();

        if( count( $columns ) == 0 || $columns == Query::SQL_WILDCARD ) {

            $this -> columns = Query::SQL_WILDCARD;

            return $this;
        }

        // An Expression?

        if( $columns instanceof Expression ) {

            $this -> columns = $columns -> getExpression();

            return $this;
        }

        // One single column?

        if( is_string( $columns ) ) {

            $this -> columns = $renderer -> columns( trim( $columns ) );

            return $this;
        }

        // A list of columns?

        if( is_array( $columns ) ) {

            $columns = array_map( 'trim', $columns );

            /**
             * Let's see if there are no mistakes like:
             *
             * array( '*' )
             */
            if( in_array( Query::SQL_WILDCARD, $columns ) ) {

                $this -> columns = Query::SQL_WILDCARD;

                return $this;
            }

            /**
             * Usage:
             *
             * <code>
             *   array( 'username' => 'long_and_complex_field_for_username' )
             * </code>
             *
             * Will become, after built the Statement:
             *
             * <code>
             *   SELECT `long_and_complex_field_for_username` AS `username`
             * </code>
             *
             * And your favorite Fetch Mode will use the Field Alias
             * as index (or property)
             */
            foreach( $columns as $alias => $column ) {

                // Building columns definition

                $this -> columns .= $renderer -> columns( $column, $alias );
            }
        }

        return $this;
    }

    /**
     * Specify the columns to be searched
     *
     * @param array|optional $tables
     *   One or more different tables to search
     *
     * @return Next\DB\Table\Select
     *   Table Select Object (Fluent Interface)
     */
    public function from( array $tables = array() ) {

        $renderer = $this -> getRenderer();

        // Building SQL Statement

        if( count( $tables ) != 0 ) {

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
            foreach( $tables as $alias => $table ) {

                $this -> tables .= $renderer -> from( $alias, $table );
            }
        }

        /**
         * @internal
         * Assembling Query
         * From this point all SQL is a Clause
         */
        $this -> createQuery( $renderer -> select( $this -> columns, $this -> tables ) );

        return $this;
    }

    /**
     * Add a JOIN Clause
     *
     * @param  string|array $table
     *  - A string with the JOIN Table
     *  - A single-index array with JOIN Table and its alias. E.g.:
     *  <code>array( 'm' => 'members' )</code>
     *
     * @param  string $on
     *   The ON Clause
     *
     * @param  string|optional $type
     *   The JOIN Type
     *
     *   The class Query interface has three values of JOIN Types:
     *   Query::INNER_JOIN, Query::LEFT_OUTER_JOIN and Query::RIGHT_OUTER_JOIN
     *
     *  However there are no constraints about what it's accepted here because there are numerous
     *  valid aliases for this values
     *
     * @return Next\DB\Table\Select
     *   Table Select Object (Fluent-Interface)
     */
    public function join( $table, $on, $type = Query::INNER_JOIN ) {

        $this -> createQuery(

            $this -> getQuery() .

            $this -> getRenderer() -> join(

                array( $table, $on, $type )
            )
        );

        return $this;
    }

    /**
     * Specify a DISTINCT Clause
     *
     * @return Next\DB\Table\Select
     *   Table Select Object (Fluent Interface)
     */
    public function distinct() {

        Query::$parts[ Query::SQL_DISTINCT ] = TRUE;

        return $this;
    }

    /**
     * Add HAVING Clause(s)
     *
     * @param array|string $condition
     *   HAVING Clause
     *
     * @param array|optional $value
     *   Value for Clause's Placeholders (if any)
     *
     * @param mixed|boolean|optional $isMatchingHaving
     *
     *   <p>If TRUE is a 'AND' HAVING Clause</p>
     *
     *   <p>If FALSE, is 'OR' HAVING Clause</p>
     *
     * @return Next\DB\Table\Select
     *   Table Select Object (Fluent Interface)
     *
     * @throws Next\DB\Table\TableException
     *   HAVING Clause is empty
     */
    public function having( $condition, array $value = array(), $isMatchingHaving = TRUE ) {

        $condition = trim( (string) $condition );

        if( empty( $condition ) ) {

            throw TableException::logic(

                'HAVING Clause Condition must be set as non-empty string'
            );
        }

        // Registering Placeholder Replacement Value

        $this -> setReplacements( (array) $value, self::SQL_HAVING );

        // Adding Clause to SQL Parts Property

        Query::$parts[ self::SQL_HAVING ][] = array(

            $condition => ( $isMatchingHaving ? self::SQL_AND : self::SQL_OR )
        );

        return $this;
    }

    /**
     * Wrapper for OR HAVING Clause
     *
     * @param array|string $condition
     *   HAVING Clause
     *
     * @param array|optional $value
     *   Value for Clause's Placeholders (if any)
     *
     * @return Next\DB\Table\Select
     *   Table Select Object (Fluent Interface)
     */
    public function orHaving( $condition, array $value = NULL ) {
        return $this -> having( $condition, $value, FALSE );
    }

    /**
     * Add a GROUP BY Clause
     *
     * @param string $field
     *   Field to group results
     *
     * @return Next\DB\Table\Select
     *   Table Select Object (Fluent Interface)
     */
    public function group( $field ) {

        if( ! self::$parts[ self::SQL_GROUP_BY ] ) {

            Query::$parts[ self::SQL_GROUP_BY ] = TRUE;

            // Registering Placeholder Replacement Value

            $this -> setReplacements(

                (array) trim( (string) $field ), self::SQL_GROUP_BY
            );
        }

        return $this;
    }

    /**
     * Reset Select Data
     *
     * Instead of resetting everything, returns a new instance of Select Object
     * Not very elegant but allow Select class to not be child of Builder anymore,
     * while Object's object bridging still restricted to method invoking
     *
     * @return Next\DB\Table\Select
     */
    public function reset() {
        return new Select;
    }
}
