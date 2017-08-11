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
 * Query Builder Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Expression extends Object {

    /**
     * Query Expression Default Options
     *
     * @var array $defaultOptions
     */
    protected $defaultOptions = array(

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
    );

    /**
     * SQL raw Expression
     *
     * @var string $expression
     */
    private $expression;

    /**
     * SQL Expression COntructor
     *
     * @param string $expression
     *  SQL Expression
     *
     * @param mixed|\Next\Components\Object|\Next\Components\Parameter|stdClass|array|optional $options
     *  Optional Configuration Options for the Query Expression
     */
    public function __construct( $expression, $options = NULL ) {

        parent::__construct( $options );

        $this -> expression =& $expression;
    }

    /**
     * Get SQL Expression
     *
     * @return string
     *  SQL Expression
     */
    public function getExpression() {
        return sprintf( '( %s )', $this -> expression );
    }
}