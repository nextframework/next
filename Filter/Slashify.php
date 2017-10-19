<?php

/**
 * Slashify Filter Class | Filter/Slashify.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Filter;

use Next\Components\Object;    # Object Class

/**
 * Adds slashes before special characters
 *
 * @package    Next\Filter
 *
 * @uses       Next\Components\Object,
 *             Next\Filter\Filterable
 */
class Slashify extends Object implements Filterable {

    /**
     * Parameter Options Definition
     *
     * @var array $parameters
     */
    protected $parameters = [

        /**
         * @internal
         *
         * Data to filter
         */
        'data'       => [ 'required' => TRUE, 'default' => NULL ],

        /**
         * @internal
         *
         * Characters list to slashify.
         * In practice, this Parameter Option configures the Filter to
         * use addslashes() instead of addcslashes().
         *
         * Also, if the list is empty and addcslashes() is used,
         * the resulting string will also pass through quotemeta()
         * to prevent that '0', 'a', 'b', 'f', 'n', 'r', 't' and 'v'
         * to become '\0', '\a', '\b', '\f', '\n', '\r', '\t' and '\v'
         * which all have special meanings in a regular expression.
         *
         * Defaults to an empty list of characters, so addslashes()
         * is used by default
         */
        'characters' => [ 'required' => FALSE, 'default' => [] ]
    ];

    // Filterable Interface Method Implementation

    /**
     * Filters input data
     *
     * @return string
     *  Input data sanitized
     */
    public function filter() {

        if( $this -> options -> data === NULL ) return;

        if( count( $this -> options -> characters ) == 0 ) {
            return addslashes( $this -> options -> data );
        }

        return quotemeta(

            addcslashes(

                $this -> options -> data,

                implode( '', $this -> options -> characters )
            )
        );
    }
}