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
     *  SQL Expression
     *
     * @param mixed|Next\Components\Object|Next\Components\Parameter|stdClass|array|optional $options
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