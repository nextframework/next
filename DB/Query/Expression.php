<?php

/**
 * Database Query Expression Class | DB\Query\Expression.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\Query;

use Next\Components\Object;     # Object Class

/**
 * A Query Expression is an SQL statement not supported by the Query Builder
 * or that needs to be rendered untouched
 *
 * @package    Next\DB
 *
 * @uses       Next\Components\Object
 */
class Expression extends Object {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [

        'expression' => [ 'required' => TRUE ],

        /**
         * @internal
         *
         * Defines whether or not the Query Expression built will
         * overwrite anything else previously defined along the
         * Expression. E.g:
         *
         * "An Application's Entity Repository predefines an ordering
         * Column but one very specific access method needs a different
         * one. Previously to this flag both ordering Clauses would
         * be rendered together, probably resulting in wrong results"
         *
         * With this flag Query Expression can tell the Query Renderer
         * that it is more important than anything else defined and
         * thus be the only thing ruling the Clause
         *
         * Defaults to FALSE because Query Expressions are specifically
         * different to each circumstance and must be taken care by
         * the developer
         */
        'overwrite' => FALSE
    ];

    /**
     * Get SQL Expression
     *
     * @return string
     *  SQL Expression
     */
    public function getExpression() : string {
        return sprintf( '( %s )', $this -> options -> expression );
    }
}