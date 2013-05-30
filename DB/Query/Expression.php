<?php

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
     * SQL raw Expression
     *
     * @var string $expression
     */
    private $expression;

    /**
     * SQL Expression COntructor
     *
     * @param string $expression
     *   SQL Expression
     */
    public function __construct( $expression ) {

        $this -> expression =& $expression;
    }

    /**
     * Get SQL Expression
     *
     * @return string
     *   SQL Expression
     */
    public function getExpression() {
        return $this -> expression;
    }
}