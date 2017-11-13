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

/**
 * Exception Classes
 */
use Next\Exception\Exceptions\InvalidArgumentExpression;

use Next\Components\Object;     # Object Class

/**
 * Query Expressions are SQL statement not supported by the Query Builder
 * or that needs to be rendered untouched
 *
 * @package    Next\DB
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentExpression
 *             Next\Components\Object
 */
class Expression extends Object {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [

        /**
         * The SQL Expression
         * Required within Next\DB\Query\Expression context, optional for
         * specific Expressions
         */
        'expression' => [ 'required' => TRUE, 'default' => NULL ],

        /**
         * @internal
         *
         * Defines whether or not the Query Expression built will
         * overwrite anything else previously defined along the
         * Expression. E.g:
         *
         * "An Application's Entity Repository predefines an ordering
         * Column but one very specific access method needs a different
         * one. Rendered together, this would probably return wrong results"
         *
         * With this flag the Query Expression can tell the Query Renderer
         * that it is more important than anything else already defined and
         * thus be the only thing ruling the Clause
         *
         * Defaults to FALSE because Query Expressions are specifically
         * different to each circumstance and must be taken care by
         * the developer
         */
        'overwrite' => [ 'required' => FALSE, 'default' => FALSE ]
    ];

    /**
     * Get SQL Expression
     *
     * @return string
     *  SQL Expression
     *
     * @throws Next\Exception\Exceptions\InvalidArgumentExpression
     *  Thrown if no Expression has been provided
     */
    public function getExpression() : string {

        if( $this -> options -> expression === NULL ) {
            throw new InvalidArgumentExpression( 'No Expression provided' );
        }

        return $this -> options -> expression;
    }
}