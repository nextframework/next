<?php

/**
 * Database Query Renderer: MySQL | DB\Query\Renderer\MySQL.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\Query\Renderer;

use Next\Components\Object;          # Object Class
use Next\Components\Types\String;    # Strings Data-type Class
use Next\DB\Query\Expression;        # Query Expression Class

/**
 * MySQL Query Renderer Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class MySQL extends Object implements Renderer {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [
        'quoteIdentifier' => [ 'required' => TRUE ],
    ];

    // CRUD-related methods

    /**
     * Render INSERT Statement
     *
     * @param string $table
     *  Table name
     *
     * @param array $fields
     *  Columns to be added in INSERT Statement
     *
     * @return string
     *  INSERT Statement
     */
    public function insert( $table, array $fields ) {

        return sprintf(

            'INSERT INTO %s ( %s ) VALUES ( %s )',

            // Table Name

            $this -> quote( $table ),

            // Table Fields

            implode( ', ', array_map( [ $this, 'quote' ], $fields ) ),

            // Fields Values

            implode(

                ', ',

                array_map(

                    function( $field ) {
                        return sprintf( ':%s', $field );
                    },

                    $fields
                )
            )
       );
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
     * @return string
     *  UPDATE Statement
     */
    public function update( $table, array $fields ) {

        return sprintf(

            'UPDATE %s SET %s',

            // Table Name

            $this -> quote( $table ),

            urldecode(

                http_build_query(

                    array_combine(

                        $fields,

                        array_map(

                            function( $field ) {
                                return sprintf( ':%s', $field );
                            },

                            $fields
                        )
                    ),

                    '', ', '
                )
            )
        );
    }

    /**
     * Render DELETE Statement
     *
     * @param string $table
     *  Table Name
     *
     * @return string
     *  DELETE Statement
     */
    public function delete( $table ) {
        return sprintf( 'DELETE FROM %s', $this -> quote( $table ) );
    }

    // Select-related Rendering Methods

    /**
     * Render SELECT Statement
     *
     * @param string $columns
     *  Columns to be included in SELECT Statement
     *
     * @param string $tables
     *  Tables from which data will be retrieved
     *
     * @param boolean|optional $isDistinct
     *  Flag to whether or not the DISTINCT Clause will be added.
     *  Defaults to FALSE
     *
     * @return string
     *  SELECT Statement
     */
    public function select( $columns, $tables, $isDistinct = FALSE ) {

        return sprintf(

            'SELECT %s%s FROM %s',

            ( $isDistinct ? self::DISTINCT . ' ' : NULL ), implode( ', ', $columns ), implode( ', ', $tables )
        );
    }

    /**
     * Render SELECT Statement Columns
     *
     * @param string $column
     *  Column name
     *
     * @param string|optional $alias
     *  Optional Column Alias
     */
    public function columns( $column, $alias = NULL ) {

        // Do we have an Expression?

        if( $column instanceof Expression ) {

            $column = $column -> getExpression();

        } else {

            // No? Then let's quote the Columns

            $column = $this -> quote( $column );
        }

        // Quoting aliases, if any

        if( ( is_string( $alias ) && ! is_numeric( $alias ) ) ) {
            $column .= sprintf( ' %s %s', self::ALIAS, $this -> quote( $alias ) );
        }

        return $column;
    }

    /**
     * Render SELECT Statement FROM Clause
     *
     * @param string $alias
     *  Table Alias
     *
     * @param string $table
     *  Table Name
     *
     * @return string
     *  SELECT Statement FROM Clause
     */
    public function from( $alias, $table ) {

        if( is_string( $alias ) && ! is_numeric( $alias ) ) {

            return sprintf( '%s %s', $this -> quote( $table ), $alias );

        } else {

            return $this -> quote( $table );
        }
    }

    /**
     * Render the WHERE Clause
     *
     * @param array $conditions
     *  WHERE Conditions
     *
     * @return string
     *  WHERE Clause
     */
    public function where( $conditions ) {

        $clause = NULL;

        if( array_key_exists( self::SQL_AND, $conditions ) ) {

            $this -> quoteColumns( $conditions[ self::SQL_AND ] );

            $clause .= implode( sprintf( ' %s ', self::SQL_AND ), $conditions[ self::SQL_AND ] );
        }

        if( array_key_exists( self::SQL_OR, $conditions ) ) {

            /**
             * @internal
             *
             * Before rendering the 'OR Clauses' we check again if 'AND Clauses' exists
             * However, instead of a code duplication, here, this servers to connect
             * both types of Clauses in one single statement
             */
            if( array_key_exists( self::SQL_AND, $conditions ) ) {
                $clause .= sprintf( ' %s ', self::SQL_AND );
            }

            $this -> quoteColumns( $conditions[ self::SQL_OR ] );

            $clause .= implode( sprintf( ' %s ', self::SQL_OR ), $conditions[ self::SQL_OR ] );
        }

        return sprintf( ' %s %s', self::WHERE, $clause );
    }

    /**
     * Render the JOIN Clause
     *
     * @param string|array $table
     *  A string with the JOIN Table or an associative single-index
     *  array for JOIN Table and its alias. E.g.: `[ 'm' => 'members' ]`
     *
     * @param string $on
     *  The ON Clause
     *
     * @param string|optional $type
     *  The JOIN Type. Defaults to INNER JOIN
     *
     * @return string
     *  JOIN Clause
     */
    public function join( $table, $on, $type = Query::INNER_JOIN ) {

        // Do we have an alias?

        if( is_array( $table ) ) {
            $table = $this -> from( key( $table ), current( $table ) );
        }

        return sprintf( ' %s %s ON( %s )', $type, $table, $on );
    }

    /**
     * Render the HAVING Clause
     *
     * @param array $conditions
     *  HAVING Clauses
     *
     * @return string
     *  HAVING Clause
     */
    public function having( array $conditions ) {

        $clause = NULL;

        array_walk(

            $conditions,

            function( $condition, $type ) use( &$clause ) {

                if( count( $condition ) == 1 && ! is_null( $clause ) ) {

                    $clause .= sprintf( ' %s %s', $type, implode( $type, $condition ) );

                } else {

                    $clause .= implode( sprintf( ' %s ', $type ), $condition );
                }
            }
        );

        return sprintf( ' %s %s', self::HAVING, $clause );
    }

    /**
     * Render the GROUP BY Clause
     *
     * @param array $fields
     *  Fields to group records
     *
     * @return string
     *  GROUP BY Clause
     */
    public function group( array $fields ) {
        return sprintf( ' %s %s', self::GROUP_BY, implode( ', ', $fields ) );
    }

    /**
     * Render the ORDER BY Clause
     *
     * @param array $fields
     *  An array with the ORDER BY rules:
     *  - If an instance of NEXT\DB\Query\Expression, the raw expression "as is"
     *  - If an associative array, as defined by
     *    \Next\DB\Query\Builder::order(), keys are the fields and
     *    values the orientations
     *
     * @return string
     *  ORDER BY Clause
     */
    public function order( array $fields ) {

        $clause = [];

        foreach( $fields as $field ) {

            if( $field instanceof Expression ) {

                $expression = $field -> getExpression();

                /**
                 * @internal
                 *
                 * Should this Expression overwrite previously
                 * defined ORDER BY Clauses?
                 */
                if( $field -> getOptions() -> overwrite !== FALSE ) {

                    $clause = [ $expression ];

                } else {

                    // OK, let's append it with them

                    $clause[] = $expression;
                }

            } else {

                $column = $this -> quote( key( $field ) );

                $clause[] = vsprintf(

                    '%s %s', [ $column, current( $field ) ]
                );
            }
        }

        return sprintf( ' %s %s', self::ORDER_BY, implode( ', ', $clause ) );
    }

    /**
     * Render the LIMIT Clause
     *
     * @param array $data
     *  LIMIT Clause data
     *
     *  First index should be the number of records and the second index
     *  the offset in which they start
     *
     * @return string
     *  LIMIT Clause
     */
    public function limit( array $data ) {
        return rtrim( sprintf( ' %s %s, %s ', self::LIMIT, $data[ 0 ], $data[ 1 ] ) );
    }

    // Query Building-related methods

    /**
     * Quote an expression with a DB Driver-specific Quote Identifier
     *
     * @param string $expression
     *  Expression to quote
     *
     * @return string
     *  Input expression, quoted
     */
    public function quote( $expression ) {

        // Do we have a full database definition (e.g. db.table)?

        if( strpos( $expression, '.' ) !== FALSE ) {

            $expression = implode(

                sprintf( '%1$s.%1$s', $this -> options -> quoteIdentifier ),

                explode( '.', $expression )
            );
        }

        $expression = new String( [ 'value' => $expression ] );

        return $expression -> quote( $this -> options -> quoteIdentifier ) -> get();
    }

    // Auxiliary Methods

    /**
     * Quotes the column name when they're in the same string as the
     * expression operator and the predicate, such as WHERE or JOIN Clauses
     *
     * @param array &$columns
     *  Statements in which to search the columns to quote
     */
    private function quoteColumns( array &$columns ) {

        foreach( $columns as $type => $condition ) {

            preg_match_all(

                '#(?<column>(\w+\.)?\w+)\s*(?<expression>OR|XOR|AND|NOT|IS|IN|BETWEEN|SOUNDS|LIKE|REGEXP|DIV|MOD|\||\&|\!|\=|\>|\<|\+|\-|\*|\/|\%|\^)#',

                $condition, $matches
            );

            $columns[ $type ] = str_replace(

                $matches['column'],

                array_map( [ $this, 'quote' ], $matches['column'] ),

                $condition
            );

            /**
             * @internal
             *
             * If named placeholders are being used and, for some reason,
             * the placeholder name matches the column name the quoting above
             * would produce something like:
             *
             * ````
             * `column` = :`column`
             * ````
             *
             * Which must be undone otherwise the Statement Class used may not
             * recognize the value after the '=' as a placeholder
             */
            $columns[ $type ] = preg_replace(

                sprintf( '#(:%1$s(.*?))%1$s#',

                $this -> options -> quoteIdentifier ), ':$2', $columns[ $type ]
            );
        }
    }
}
