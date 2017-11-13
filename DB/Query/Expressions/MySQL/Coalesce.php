<?php

/**
 * MySQL Query Expression Class: COALESCE | DB\Query\Expressions\MySQL\Coalesce.php
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
 * Implementation of MySQL COALESCE() Expression
 *
 * @package    Next\DB
 *
 * @uses       Next\Components\Object
 *             Next\DB\Query\Expression
 */
class Coalesce extends Expression {

    /**
     * Get SQL Expression
     *
     * @return string
     *  SQL Expression
     */
    public function getExpression() : string {

        return strtr(

            sprintf(

                'COALESCE( \'%s\' )',

                implode( '\', \'',

                    array_merge(

                        (array) $this -> options -> values,

                        [ ( $this -> options -> default ?? 'NULL' ) ]
                    )
                )
            ),

            [ '\'NULL\'' => 'NULL' ]
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
            'values' => [ 'required' => TRUE ],

            /**
             * Default value to be added as the last argument in the
             * COALSECE Function and used if none of the values passed match
             * Defaults an uppercased 'NULL' (no quotes)
             */
            'default' => [ 'required' => FALSE, 'default' => 'NULL' ]
        ];
    }
}