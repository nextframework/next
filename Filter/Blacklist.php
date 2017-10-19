<?php

/**
 * Blacklist Filter Class | Filter/Blacklist.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Filter;

use Next\Components\Object;    # Object Class

/**
 * Blacklists words that cannot be part of input string
 *
 * @package    Next\Filter
 *
 * @uses       Next\Components\Object,
 *             Next\Filter\Filterable
 */
class Blacklist extends Object implements Filterable {

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
        'data'  => [ 'required' => TRUE, 'default' => NULL ],

        /**
         * @internal
         *
         * Words to blacklist
         */
        'words' => [ 'required' => FALSE, 'default' => [] ]
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

        if( count( $this -> options -> words ) == 0 ) {
            return $this -> options -> data;
        }

        return preg_replace(

            sprintf(

                '/\s*\b(backticks)\s*\b/',

                implode( '|', (array) $this -> options -> words )

            ), '', $this -> options -> data
        );
    }
}