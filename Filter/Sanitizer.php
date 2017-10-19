<?php

/**
 * Sanitizer Collection Class | Filter\Sanitizer.php
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

use Next\Components\Object;                            # Object Class
use Next\Components\Collections\AbstractCollection;    # Abstract Collection Class

/**
 * The Sanitizer is a Collection of Filters to be applied sequentially
 *
 * @package    Next\Filter
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Filter\Filterable
 *             Next\Components\Object
 *             Next\Components\Collections\AbstractCollection
 */
class Sanitizer extends AbstractCollection implements Filterable {

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
        'data' => [ 'required' => TRUE ],
    ];

    // Filterable Method Implementation

    /**
     * Filters data
     *
     * @return string
     *  Input string filtered accordingly to the Filters added
     *  to Sanitizer Collection
     */
    public function filter() {

        $data = $this -> options -> data;

        foreach( $this -> getIterator() as $filter ) {

            // Passing data to be validated to the Filter

            $filter -> getOptions() -> merge( [ 'data' => $data ] );

            $data = $filter -> filter();
        }

        return $data;
    }

    // Abstract Method Implementation

    /**
     * Check Object acceptance
     *
     * Check if given Filter is acceptable in Sanitizer Collection
     * To be valid, the Filter must implement `\Next\Filter\Filterable`
     * Interface
     *
     * @param \Next\Components\Object $object
     *  An Object object
     *  The checking for required interface will be inside the method
     *
     * @return boolean
     *  TRUE if given Object is acceptable by Sanitizer Collection
     *  and FALSE otherwise
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Given Filter is not acceptable in the Sanitizer Collection
     */
    public function accept( Object $object ) {

        if( ! $object instanceof Filterable ) {

            return new InvalidArgumentException(

                sprintf(

                    '<strong>%s</strong> is not a valid Filter

                    Filters must implement <em>Next\Filter\Filterable</em>
                    Interface',

                    $object
                )
            );
        }

        return TRUE;
    }
}