<?php

namespace Next\DB\Query\Renderer;

use Next\DB\Query\Expression;    # Query Expression Class

/**
 * MySQL Query Renderer Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class MySQL extends AbstractRenderer {

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

            implode( ', ', array_map( array( $this, 'quote' ), $fields ) ),

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

        if( $column instanceof Expression ) {
            $column = $column -> getExpression();
        }

        elseif( strpos( $column, '.' ) === FALSE ) {
            $column = $this -> quote( $column );
        }

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

        return sprintf( ' %s %s', self::WHERE, $clause );
    }

    /**
     * Render the JOIN Clause
     *
     * @param  string|array $table
     *  - A string with the JOIN Table
     *  - An associative single-index array for JOIN Table and its alias. E.g.:
     *  <code>array( 'm' => 'members' )</code>
     *
     * @param  string $on
     *  The ON Clause
     *
     * @param  string|optional $type
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
     *  An associative array, where keys are the fields and values orientations
     *
     * @return string
     *  ORDER BY Clause
     */
    public function order( array $fields ) {

        $clause = array();

        $orientation = self::ORDER_ASCENDING;   # Default orientation

        array_walk_recursive(

            $fields,

            function( $value, $key ) use( &$clause, $orientation ) {

                $clause[] = vsprintf(

                    '%s %s',

                    ( is_int( $key ) ? array( $value, $orientation ) : array( $key, $value ) )
                );
            }
        );

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
        return sprintf( ' %s %s, %s ', self::LIMIT, $data[ 0 ], $data[ 1 ] );
    }
}
