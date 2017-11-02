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

use Next\Components\Object;               # Object Class
use Next\Components\Collections\Lists;    # Abstract Collection Class

/**
 * The Sanitizer is a Collection of Filters to be applied sequentially
 *
 * @package    Next\Filter
 *
 * @uses       Next\Exception\Exceptions\InvalidArgumentException
 *             Next\Components\Object
 *             Next\Components\Collections\Collection
 *             Next\Filter\Filterable
 */
class Sanitizer extends Lists implements Filterable {

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
        'data'   => [ 'required' => FALSE ],

        'silent' => [ 'required' => FALSE, 'default' => TRUE ]
    ];

    // Filterable Method Implementation

    /**
     * Filters input data
     *
     * @return string
     *  Input string filtered accordingly to the Filters added
     *  to Sanitizer Collection
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Thrown if anythign wrong occurs with any of Next\Filter\Filterable`
     *  Objects added to The Sanitizer's Collection — which *should* raise a
     *  `InvalidArgumentException` — -AND- we're running in DEVELOPMENT MODE
     *  -OR- The Sanitizer has been configured -NOT- silence Exceptions
     */
    public function filter() : string {

        if( $this -> options -> data === NULL ) {
            throw new InvalidArgumentException( 'Nothing to filter' );
        }

        $data = $this -> options -> data;

        foreach( $this -> getIterator() as $filter ) {

            // Injecting data to be filtered

            $filter -> options -> data = $data;

            try {

                $data = $filter -> filter();

            } catch( InvalidArgumentException $e ) {

                /**
                 * @internal
                 *
                 * If an Next\Exceptions\Exceptions\InvalidArgumentException
                 * is caught The Sanitizer can't continue applying any other
                 * filter in the chain 'cause will, probably, throw Exceptions
                 * as well, so we abort
                 *
                 * But we'll only silence it if we have been told to do so -OR-
                 * we're in DEVELOPMENT MODE
                 */
                if( ( defined( 'DEVELOPMENT_MODE' ) && DEVELOPMENT_MODE >= 1 ) ||
                        ! $this -> options -> silent ) {

                    throw $e;
                }

                /**
                 * @internal
                 *
                 * Otherwise just interrupt the iteration.
                 * To respect PHP 7 return Type Declarations we `return` instead
                 * of the most appropriate `break`
                 */
                break;
            }
        }

        return $data;
    }

    /**
     * Wrapper method to allow injection of data to be filtered from outside
     * Sanitizer Context
     *
     * It's purely lexical, since the Parameter Object has both Overloading
     * \ArrayAccess Interface implementations the routine below could
     * be manually done
     *
     * @param mixed $data
     *  Data to be filtered
     *
     * @return \Next\Filter\Sanitizer
     *  Sanitizer Object (Fluent Interface)
     */
    public function setData( $data ) : Sanitizer {

        $this -> options -> data = $data;

        return $this;
    }

    // Abstract Method Implementation

    /**
     * Check Object acceptance
     *
     * Check if given Filter is acceptable in Sanitizer Collection
     * To be valid, the Filter must implement Next\Filter\Filterable`
     * Interface
     *
     * @param \Next\Components\Object $object
     *  An Object object
     *  The checking for required interface will be inside the method
     *
     * @return boolean
     *  TRUE if given Object is acceptable in Sanitizer's Collection.
     *  If not an Next\Exception\Exceptions\InvalidArgumentException
     *  will be thrown instead
     *
     * @throws \Next\Exception\Exceptions\InvalidArgumentException
     *  Given Object is not acceptable in a Sanitizer's Collection
     */
    public function accept( Object $object ) : bool {

        if( ! $object instanceof Filterable ) {

            throw new InvalidArgumentException(

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