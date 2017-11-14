<?php

/**
 * MySQL Query Expression Class: FIELD | DB\Query\Expressions\MySQL\Field.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\Query\Expressions\MySQL;

use Next\Components\Object;      # Object Class
use Next\DB\Query\Expression;    # Query Expressions Class

/**
 * Implementation of MySQL FIELD() Expression
 *
 * @package    Next\DB
 *
 * @uses       Next\Components\Object
 *             Next\DB\Query\Expression
 */
class Field extends Expression {

    /**
     * Get SQL Expression
     *
     * @return string
     *  SQL Expression
     */
    public function getExpression() : string {

        return sprintf(

            'FIELD( %1$s, %3$s%2$s%3$s )',

            $this -> options -> column,

            implode(

                sprintf(

                    '%1$s, %1$s',

                    ( $this -> options -> quote !== FALSE ? "'" : NULL )
                ),

                (array) $this -> options -> list
            ),

            ( $this -> options -> quote !== FALSE ? "'" : NULL )
        );
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
             * Database Column containing the string to be searched in a
             * list of strings
             */
            'column' => [ 'required' => TRUE ],

            /**
             * List of strings where to search the column.
             * It can be a single string (for whatever reasons) or an array
             * with a list of strings
             */
            'list' => [ 'required' => TRUE ],

            /**
             * Tells the Expression Builder to surround each value passed to
             * Parameter Options 'list' with single quotes.
             * Defaults to TRUE, because quotes must not be added only when the
             * FIELD() Function is used over Table Columns rather then raw data,
             * case in which, for example, backticks, must be preserved,
             * otherwise MySQL will respond with an invalid usage of the
             * function — i.e. '`table`.`column`', with surrounding quotes
             */
            'quote' => [ 'required' => FALSE, 'default' => TRUE ]
        ];
    }
}