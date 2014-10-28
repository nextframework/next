<?php

namespace Next\DB\Query\Renderer;

use Next\DB\Query\Query;    # Query Renderer Interface

/**
 * Query Renderer Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Renderer extends Query {

    /**
     * Render INSERT Statement
     *
     * @param string $table
     *   Table name
     *
     * @param array $fields
     *   Columns to be added in INSERT Statement
     */
    public function insert( $table, array $fields );

    /**
     * Render UPDATE Statement
     *
     * @param string $table
     *   Table name
     *
     * @param array $fields
     *   Columns to be added in UPDATE Statement
     */
    public function update( $table, array $fields );

    /**
     * Render DELETE Statement
     *
     * @param string $table
     *   Table Name
     */
    public function delete( $table );

    // Select-related Rendering Methods

    /**
     * Render SELECT Statement
     *
     * @param string $columns
     *   Columns to be included in SELECT Statement
     *
     * @param string $tables
     *   Tables from which data will be retrieved
     */
    public function select( $columns, $tables );

    /**
     * Render SELECT Statement Columns
     *
     * @param string $column
     *   Column name
     *
     * @param string $alias
     *   Optional Columns Aliases
     */
    public function columns( $column, $alias );

    /**
     * Render SELECT Statement FROM Clause
     *
     * @param string $alias
     *   Table Alias
     *
     * @param string $table
     *   Table Name
     */
    public function from( $alias, $table );

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
     */
    public function distinct( $query, $isDistinct = FALSE );

    /**
     * Render the HAVING Clause
     *
     * @param array $having
     *   HAVING Clauses
     */
    public function having( array $having );

    /**
     * Render the GROUP BY Clause
     *
     * @param string $field
     *   Field to group records
     */
    public function group( $field );

    /**
     * Render the WHERE Clause
     *
     * @param array $where
     *   WHERE Clauses
     */
    public function where( array $where );

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
     */
    public function order( array $data );

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
     */
    public function limit( array $data );

    /**
     * Render the JOIN Clause
     *
     * @param array $data
     *  JOIN Data
     */
    public function join( array $join );
}
