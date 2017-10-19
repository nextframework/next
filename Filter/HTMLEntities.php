<?php

/**
 * HTML Entities Filter Class | Filter/HTMLEntities.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\Filter;

use Next\Components\Object;    # Object Class

/**
 * Encodes HTML Entities and, optionally, stripes out backticks from
 * given string
 *
 * @package    Next\Filter
 *
 * @uses       Next\Components\Object,
 *             Next\Filter\Filterable
 */
class HTMLEntities extends Object implements Filterable {

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
        'data'           => [ 'required' => TRUE, 'default' => NULL ],

        /**
         * @internal
         *
         * A bitmask of flags which specify how to handle quotes,
         * invalid code unit sequences and the used document type
         * It's equivalent to htmlentities() / htmlspecialchars()
         * second argument.
         *
         * Defaults to `ENT_QUOTES | ENT_HTML5` which converts both
         * double and single qutes and handles the code as HTML5
         */
        'quoteStyle'     => [ 'required' => FALSE, 'default' => ENT_QUOTES | ENT_HTML5 ],

        /**
         * @internal
         *
         * Defines the encoding used when converting characters.
         * It's equivalent to htmlentities() / htmlspecialchars()
         * third argument.
         *
         * Defaults to `UTF-8`, the default encoding for PHP 5.4+
         */
        'encoding'       => [ 'required' => FALSE, 'default' => 'UTF-8' ],

        /**
         * @internal
         *
         * Defines whether or not entities already encoded will be
         * encoded again.
         * It's equivalent to htmlentities() / htmlspecialchars()
         * fourth argument.
         *
         * Defaults to TRUE, as in htmlentities() / htmlspecialchars()
         */
        'double'         => [ 'required' => FALSE, 'default' => TRUE ],

        /**
         * @internal
         *
         * Defines whether or not a all HTML Entities will be encoded
         * or just those with some HTML meaning
         * In practice this Parameter Option configures the Filter to
         * use htmlentities() instead of htmlspecialchars()
         *
         * Defaults to FALSE, meaning htmlspecialchars() will be used
         */
        'full'           => [ 'required' => FALSE, 'default' => FALSE ],

        /**
         * @internal
         *
         * Defines whether or not backticks (`) will be striped out
         * the resulting string
         *
         * Defaults to TRUE as a low level remedy to SQL Injections,
         * even though backticks have no effect on prepared statements
         */
        'stripBackticks' => [ 'required' => FALSE, 'default' => TRUE ]
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

        if( $this -> options -> full !== FALSE ) {

            $data = htmlentities(
                $this -> options -> data,
                $this -> options -> quoteStyle,
                $this -> options -> encoding,
                $this -> options -> double
            );

        } else {

            $data = htmlspecialchars(
                $this -> options -> data,
                $this -> options -> quoteStyle,
                $this -> options -> encoding,
                $this -> options -> double
            );
        }

        return ( $this -> options -> stripBackticks !== FALSE ? strtr( $data, [ '`' => '' ] ) : $data );
    }
}