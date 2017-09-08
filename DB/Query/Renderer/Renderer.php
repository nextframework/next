<?php

/**
 * Database Query Renderers Interface | DB\Query\Renderer\Renderer.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
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

    // CRUD-related methods

    /**
     * Render INSERT Statement
     *
     * @param string $table
     *  Table name
     *
     * @param array $fields
     *  Columns to be added in INSERT Statement
     */
    public function insert( $table, array $fields );

    /**
     * Render UPDATE Statement
     *
     * @param string $table
     *  Table name
     *
     * @param array $fields
     *  Columns to be added in UPDATE Statement
     */
    public function update( $table, array $fields );

    /**
     * Render DELETE Statement
     *
     * @param string $table
     *  Table Name
     */
    public function delete( $table );

    // Select-related Rendering Methods

    /**
     * Render SELECT Statement
     *
     * @param string $columns
     *  Columns to be included in SELECT Statement
     *
     * @param string $tables
     *  Tables from which data will be retrieved
     */
    public function select( $columns, $tables );

    /**
     * Render SELECT Statement Columns
     *
     * @param string $column
     *  Column name
     *
     * @param string|optional $alias
     *  Optional Column Alias
     */
    public function columns( $column, $alias = NULL );

    /**
     * Render SELECT Statement FROM Clause
     *
     * @param string $alias
     *  Table Alias
     *
     * @param string $table
     *  Table Name
     */
    public function from( $alias, $table );

    /**
     * Render the WHERE Clause
     *
     * @param array $conditions
     *  WHERE Conditions
     */
    public function where( $conditions );

    /**
     * Render the HAVING Clause
     *
     * @param array $conditions
     *  HAVING Clauses
     */
    public function having( array $conditions );

    /**
     * Render the GROUP BY Clause
     *
     * @param array $fields
     *  Fields to group records
     */
    public function group( array $fields );

    /**
     * Render the ORDER BY Clause
     *
     * @param array $fields
     *  An associative array, where keys are the fields and values orientations
     */
    public function order( array $fields );

    /**
     * Render the LIMIT Clause
     *
     * @param array $data
     *  LIMIT Clause data.
     *
     *  First index should be the number of records and the second index
     *  the offset in which they start
     */
    public function limit( array $data );

    /**
     * Render the JOIN Clause
     *
     * @param string|array $table
     *  - A string with the JOIN Table
     *  - An associative single-index array for JOIN Table and its alias. E.g.:
     *  `[ 'm' => 'members' ]`
     *
     * @param string $on
     *  The ON Clause
     *
     * @param string|optional $type
     *  The JOIN Type. Defaults to INNER JOIN
     */
    public function join( $table, $on, $type = Query::INNER_JOIN );
}
