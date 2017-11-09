<?php

/**
 * MySQL Query Expression Class: JSON_EXTRACT | DB\Query\Expressions\MySQL\JSON\Extract.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\Query\Expressions\MySQL\JSON;

use Next\Components\Object;      # Object Class
use Next\DB\Query\Expression;    # Query Expressions Class

/**
 * Implementation of MySQL JSON_EXTRACT() Expression
 *
 * @package    Next\DB
 *
 * @uses       Next\Components\Object
 *             Next\DB\Query\Expression
 */
class Extract extends Expression {

    /**
     * Get SQL Expression
     *
     * @return string
     *  JSON Extract Expression
     */
    public function getExpression() : string {

        if( $this -> options -> legacy !== FALSE ) return $this -> legacy();

        return sprintf(

            '%1$s%3$s\'%2$s\'',

            $this -> options -> column, $this -> options -> path,

            ( $this -> options -> unquote !== FALSE ? '->>' : '->' )
        );
    }

    // Auxiliary Methods

    /**
     * Build the Expression for older MySQL versions, suing JSON_EXTRACT()
     * instead of the `->` syntax
     *
     * @return string
     *  JSON EXTRACT Expression
     */
    protected function legacy() : string {

        $expression = sprintf(

            'JSON_EXTRACT( %s, %s )',

            $this -> options -> column, $this -> options -> path
        );

        if( $this -> options -> unquote !== FALSE ) {
            return sprintf( 'JSON_UNQUOTE( %s )', $expression );
        }

        return $expression;
    }

    // Parameterizable Interface Method Overwriting

    /**
     * Set up Expression Options
     *
     * @return array
     *  JSON Extract Expression Options
     */
    public function setOptions() : array {

        return [

            /**
             * Database Column containing the JSON string from which
             * data will be extracted
             */
            'column' => [ 'required' => TRUE ],

            /**
             * JSON Path to extract data
             */
            'path' => [ 'required' => TRUE ],

            /**
             * Configures the Expression to unquote the resulting match(es).
             * In practice it uses MySQL `->>` instead of `->`
             * Defaults to TRUE in favour of the new syntax
             *
             * Note! This feature is available from MySQL 5.7.9 and up
             */
            'unquote' => [ 'required' => FALSE, 'default' => TRUE ],

            /**
             * Configures the Expression to extract JSON data with legacy
             * syntax — i.e. using JSON_EXTRACT instead of the shorthand `->`
             * Defaults to FALSE in favour of the new syntax
             */
            'legacy' => [ 'required' => FALSE, 'default' => FALSE ]
        ];
    }
}