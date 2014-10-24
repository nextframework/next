<?php

namespace Next\DB\Query\Renderer;

use Next\DB\Query\Expression;    # Expression Class

/**
 * MySQL Query Renderer Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class MySQL extends AbstractRenderer {

    // Mapper-related Rendering Methods

    /**
     * Render INSERT Statement
     *
     * @param string $table
     *   Table name
     *
     * @param array $fields
     *   Columns to be added in INSERT Statement
     *
     * @return string
     *   INSERT Statement
     */
    public function insert( $table, array $fields ) {

        return sprintf(

                   'INSERT INTO %s ( %s ) VALUES ( %s )',

                   // Table Name

                   sprintf( '%s%s%s', $this -> quoteIdentifier, $table, $this -> quoteIdentifier ),

                   // Table Fields

                   sprintf(

                       sprintf( '%s%%s%s', $this -> quoteIdentifier, $this -> quoteIdentifier ),

                       implode(
                           sprintf( '%s, %s', $this -> quoteIdentifier, $this -> quoteIdentifier ), $fields
                       )
                   ),

                   // Fields Values

                   implode( ', ', array_fill( 0, count( $fields ), '?' ) )
               );
    }

    /**
     * Render UPDATE Statement
     *
     * @param string $table
     *   Table name
     *
     * @param array $fields
     *   Columns to be added in UPDATE Statement
     *
     * @return string
     *   UPDATE Statement
     */
    public function update( $table, array $fields ) {

        return sprintf(

           'UPDATE %s SET %s',

           // Table Name

           sprintf( '%s%s%s', $this -> quoteIdentifier, $table, $this -> quoteIdentifier ),

           // Table Fields and their new Values

           urldecode(

               http_build_query(

                   array_combine(

                       $fields,

                       array_fill( 0, count( $fields ), '?' )
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
     *   Table Name
     *
     * @return string
     *   DELETE Statement
     */
    public function delete( $table ) {
        return sprintf( 'DELETE FROM %s%s%s', $this -> quoteIdentifier, $table, $this -> quoteIdentifier );
    }

    // Select-related Rendering Methods

    /**
     * Render SELECT Statement
     *
     * @param string $columns
     *   Columns to be included in SELECT Statement
     *
     * @param string $tables
     *   Tables from which data will be retrieved
     *
     * @return string
     *   SELECT Statement
     */
    public function select( $columns, $tables ) {
        return sprintf( 'SELECT #%s FROM %s', rtrim( $columns, ', ' ), rtrim( $tables, ', ' ) );
    }

    /**
     * Render SELECT Statement Columns
     *
     * @param string $alias
     *   Columns Aliases
     *
     * @param string $column
     *   Column name
     *
     * @return string
     *   SELECT Statement Columns
     */
    public function columns( $column, $alias = NULL ) {

        // Keep Expressions untouched

        if( $column instanceof Expression ) {

            $columns = $column -> getExpression();

        } elseif( strpos( $column, '.' ) !== FALSE ) {

            // As well with Implicit Join Notation

            $columns =& $column;

        } else {

            // Adding quote identifier to everything else

            $columns = sprintf(

                '%s%s%s',

                $this -> quoteIdentifier, $column, $this -> quoteIdentifier
            );
        }

        // Adding alias, if any

        if( ( is_string( $alias ) && ! is_numeric( $alias ) ) ) {

            $columns .= sprintf(

                ' %s %s%s%s, ',

                self::SQL_AS,

                $this -> quoteIdentifier, $alias, $this -> quoteIdentifier
            );
        }

        return sprintf( '%s, ', $columns );
    }

    /**
     * Render SELECT Statement FROM Clause
     *
     * @param string $alias
     *   Table Alias
     *
     * @param string $table
     *   Table Name
     *
     * @return string
     *   SELECT Statement FROM Clause
     */
    public function from( $alias, $table ) {

        // If we have a Table Alias, it cannot be wrapped into backticks

        if( is_string( $alias ) && ! is_numeric( $alias ) ) {

            return sprintf( '%s%s%s %s,', $this -> quoteIdentifier, $table, $this -> quoteIdentifier, $alias );

        } else {

            return sprintf( '%s%s%s, ', $this -> quoteIdentifier, $table, $this -> quoteIdentifier );
        }
    }

    /**
     * Render the DISTINCT Clause.
     *
     * <p>This Clause is not rendered in the same way as the others.</p>
     *
     * <p>
     *     Instead that we just replace an Internal Placeholder with
     *     DISTINCT Keyword if dealing with this type of SELECT Statement,
     *     or we remove the PlaceHolder itself, if don't
     * </p>
     *
     * @param string $query
     *   SQL Statement built so far
     *
     * @param boolean $isDistinct
     *   Flag to define should be done: Add the keyword, or remove the Placeholder
     *
     * @return string
     *   SELECT Statement DISTINCT Clause -OR- without the Placeholder
     */
    public function distinct( $query, $isDistinct = FALSE ) {

        if( $isDistinct ) {

            return str_replace(

                '#', sprintf( '%s ', self::SQL_DISTINCT ), $query
            );

        } else {

            return str_replace( '#', '', $query );
        }
    }

    /**
     * Render the HAVING Clause
     *
     * @param array $having
     *   HAVING Clauses
     *
     * @return string
     *   SELECT Statement HAVING Clause
     */
    public function having( array $having ) {

        // No Conditions to deal. Just return

        if( count( $having ) == 0 ) {
            return NULL;
        }

        // Building WHERE Clauses

        $return = NULL;

        foreach( $having as $index => $clauses ) {

            $clause = key( $clauses );

            // The first Clause has no connector

            if( $index == 0 ) {

                $return .= $clause;

            } else {

                $return .= sprintf( ' %s %s ', current( $clauses ), $clause );
            }
        }

        return ( ! is_null( $return ) ? sprintf( '%s %s ', self::SQL_HAVING, rtrim( $return ) ) : NULL );
    }

    /**
     * Render the GROUP BY Clause
     *
     * @return string
     *   GROUP BY Clause
     */
    public function group() {
        return sprintf( '%s ? ', self::SQL_GROUP_BY  );
    }

    /**
     * Render the WHERE Clause
     *
     * @param array $where
     *   WHERE Clauses
     *
     * @return string
     *   WHERE Clause
     */
    public function where( array $where ) {

        // No Conditions to deal. Just return

        if( count( $where ) == 0 ) {
            return NULL;
        };

        // Building WHERE Clauses

        $return = NULL;

        foreach( $where as $index => $clauses ) {

            $clause = key( $clauses );

            // The first Clause has no connector

            if( $index == 0 ) {

                $return .= $clause;

            } else {

                $return .= sprintf( ' %s %s', current( $clauses ), $clause );
            }
        }

        return ( ! is_null( $return ) ? sprintf( '%s %s ', self::SQL_WHERE, rtrim( $return ) ) : NULL );
    }

    /**
     * Render the ORDER BY Clause
     *
     * @param array $data
     *
     *   <p>Information about ordenation:</p>
     *
     *   <p>
     *       Keys are the fields which will lead the ordenation and
     *       the Values the ordening directions: ASC or DESC
     *   </p>
     *
     * @return string
     *   ORDER BY Clause
     */
    public function order( array $data ) {

        return sprintf(

            '%s %s ',

            self::SQL_ORDER_BY,

            str_replace( '=', ' ', http_build_query( $data, NULL, ', ' ) )
        );
    }

    /**
     * Render the LIMIT Clause
     *
     * @param array $data
     *
     *   <p>Information about limitations:</p>
     *
     *   <p>
     *       First index is the offset to start results
     *       Second Index is the number of records to be returned
     *   </p>
     *
     * @return string
     *   LIMIT Clause
     */
    public function limit( array $data ) {
        return sprintf( '%s %s, %s ', self::SQL_LIMIT, $data[ 0 ], $data[ 1 ] );
    }

    /**
     * Render the JOIN Clause
     *
     * @param array $data
     *  JOIN Data
     *
     * @return string
     *   JOIN Clause
     */
    public function join( array $join ) {

        $query = NULL;

        list( $tables, $on, $type ) = $join;

        // Do we have a table alias?

        if( is_array( $tables ) ) {

            $t = NULL;

            foreach( $tables as $alias => $table ) {

                $t .= $this -> from( $alias, $table );
            }

            $tables = $t;
        }

        $query .= sprintf( ' %s %s ON( %s )', $type, trim( $tables, ',' ), $on );

        return $query;
    }
}
