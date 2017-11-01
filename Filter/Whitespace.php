<?php

/**
 * Whitespace Filter Class | Filter/Whitespace.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Filter;

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Object;    # Object Class

/**
 * Removes unnecessary spaces from input string
 *
 * @package    Next\Filter
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Components\Object,
 *             Next\Filter\Filterable
 */
class Whitespace extends Object implements Filterable {

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
         * Configures the Filter to remove boundary whitespaces from the left
         * Defaults to TRUE.
         */
        'left' => [ 'required' => FALSE, 'default' => TRUE ],

        /**
         * @internal
         *
         * Configures the Filter to remove boundary whitespaces from the right.
         * Defaults to TRUE
         */
        'right' => [ 'required' => FALSE, 'default' => TRUE ],

        /**
         * @internal
         *
         * Configures the Filter to remove duplicated whitespaces between words.
         * Defaults to TRUE
         */
        'between' => [ 'required' => FALSE, 'default' => TRUE ]
    ];

    // Filterable Interface Method Implementation

    /**
     * Filters input data
     *
     * @return string
     *  Input data with whitespaces removed
     *
     * @see \Next\Filter\Blacklist
     *  Detailed explanation on why the Exception is thrown
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if Parameter Option 'data' has no value (see above)
     */
    public function filter() : string {

        if( $this -> options -> data === NULL ) {
            throw new InvalidArgumentException( 'Nothing to filter' );
        }

        $data = $this -> options -> data;

        if( $this -> options -> between !== FALSE ) {
            $data = preg_replace( '/\b\s{2,}\b/', ' ', $data );
        }

        if( $this -> options -> left !== FALSE ) {
            return ( $this -> options -> right ? trim( $data ) : ltrim( $data ) );
        }

        return ( $this -> options -> right !== FALSE ? rtrim( $data ) : $data );
    }
}