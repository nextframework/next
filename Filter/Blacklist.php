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

/**
 * Exception Class(es)
 */
use Next\Exception\Exceptions\InvalidArgumentException;

use Next\Components\Object;    # Object Class

/**
 * Blacklists words that cannot be part of input string, stripping them off
 *
 * @package    Next\Filter
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Components\Object,
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
     *  Input data with blacklisted words removed.
     *  If input data is `NULL` then an Exception is thrown. E.g:
     *
     *  ````
     *  $sanitizer = new Next\Filter\Sanitizer(
     *    [ 'data' => "A 'quote' is <b>bold</b> with `some backticks`!" ]
     *  );
     *
     *  $sanitizer -> add( new Next\Filter\Blacklist( [ 'words' => [ 'backticks' ] ] ) );
     *
     *  var_dump( $sanitizer -> filter() ); // A 'quote' is <b>bold</b> with `some`!
     *  ````
     *
     * In the example above we have a Sanitizer Collection that
     * operates with multiple `Next\Filter\Filterable` Objects, even
     * though, here, only `Next\Filter\Blacklist` has been used
     *
     * From the `Next\Components\Parameter` point-of-view, 'data' is
     * a required Parameter, and isolate, `Next\Filter\Blacklist` will not
     * pass `Parameter::verify()`, but from a `Next\Filter\Filter`
     * point-of-view it can be nullified for late injection during
     * Sanitizer's iteration
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if Parameter Option 'data' has no value (see above)
     */
    public function filter() : string {

        if( $this -> options -> data === NULL ) {
            throw new InvalidArgumentException( 'Nothing to filter' );
        }

        if( count( $this -> options -> words ) == 0 ) {
            return $this -> options -> data;
        }

        return preg_replace(

            sprintf(

                '/\s*\b(%s)\s*\b/',

                implode( '|', (array) $this -> options -> words )

            ), '', $this -> options -> data
        );
    }
}