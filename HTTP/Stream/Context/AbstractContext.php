<?php

/**
 * HTTP Stream Context Abstract Class | HTTP\Stream\Context\AbstractContext.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Stream\Context;

use Next\Components\Utils\ArrayUtils;    # Array Utils Class

/**
 * Base structure for an HTTP Stream Context
 *
 * @package    Next\HTTP
 *
 * @uses       Next\Components\Utils\ArrayUtils
 *             Next\HTTP\Stream\Context\Context
 *
 * @deprecated
 * @FIXME
 */
abstract class AbstractContext implements Context {

    /**
     * Stream Context Resource
     *
     * @var resource $context
     */
    protected $context;

    /**
     * Stream Context Options
     *
     * @var array $options
     */
    protected $options = [];

    /**
     * Stream Context Constructor
     *
     * @param array|optional $options
     *  Initial Context Options
     */
    public function __construct( array $options = [] ) {

        // Setting Options, if any...

        if( count( $options ) != 0 ) {

            /**
             * @internal
             *
             * \Next\Stream\Context\AbstractContext::setOptions() is not being used here
             * because when constructing the object no options were
             * defined yet
             */
            $this -> options = $options;
        }
    }

    /**
     * Set more (or late) Stream Context Options
     *
     * @param array $options
     *  Context Options
     *
     * @return \Next\HTTP\Stream\Context\Context
     *  Stream Context Object (Fluent Interface)
     */
    public function setOptions( array $options ) {

        $this -> options = ArrayUtils::union( $this -> options, $options );

        return $this;
    }

    /**
     * Get Context Options
     *
     * @param string|optional $option
     *  Desired Context Option
     *
     * @param string|optional $wrapper
     *  Optional Context Option Wrapper
     *
     * @return array|boolean
     *
     *  If <strong>$option</strong> is NOT null and we can't find a match
     *  option FALSE is returned. Otherwise the desired option value
     *  will.
     *
     *  If <strong>$option</strong> argument IS null, all the options defined will be
     *  returned
     *
     *   <strong>$wrapper</strong> argument, if set, can restrict the search and thus avoid a
     *  value to be found
     */
    public function getOptions( $option = NULL, $wrapper = NULL ) {

        if( $option !== NULL ) {

            // Looking for the array key where the option COULD be

            $key = ArrayUtils::search( $this -> options, $option, $wrapper );

            // If it exists, let's return it

            if( $key != -1 && isset( $this -> options[ $key ][ $option ] ) ) {

                return $this -> options[ $key ][ $option ];
            }

            return FALSE;
        }

        return $this -> options;
    }

    // Interface Method Implementation

    /**
     * Get Context Resource
     *
     * @return resource
     *  Stream Context Resource
     */
    public function getContext() {

        // Create Stream Context if not created yet

        if( $this -> context === NULL ) {
            $this -> context = $this -> createContext();
        }

        return $this -> context;
    }

    // Abstract Methods Definition

    /**
     * Create a Stream Context
     */
    abstract protected function createContext();
}