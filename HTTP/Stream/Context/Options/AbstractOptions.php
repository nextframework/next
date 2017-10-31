<?php

/**
 * HTTP Stream Context Options Abstract Class | HTTP\Stream\Context\Options\AbstractOptions.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Stream\Context\Options;

use Next\Components\Object;    # Object Class

/**
 * Base structure for all HTTP Stream Context Options Classes
 *
 * @package    Next\HTTP
 *
 * @uses       Next\Components\Object
 *             Next\HTTP\Stream\Context\Options\Option
 *
 * @deprecated
 *
 * @FIXME
 */
abstract class AbstractOptions extends Object implements Option {

    /**
     * Context Options Values
     *
     * @var array $values
     */
    private $values = [];

    /**
     * Context Options Constructor
     *
     * @param string|array $option
     *  Context Option Name
     *
     * @param string|optional $value
     *  Context Option Value
     */
    public function __construct( $option, $value = NULL ) {

        $this -> setValue( $option, $value );
    }

    // Accessors

    /**
     * Get COntext Options Values
     *
     * @return array
     *  Context Options
     */
    public function getValues() {
        return $this -> values;
    }

    // Auxiliary Methods

    /**
     * Set Context Option Value
     *
     * @param string|array $option
     *  Context Option Name
     *
     * @param string|optional $value
     *  Context Option Value
     */
    private function setValue( $option, $value = NULL ) {

        // Recursion...

        if( (array) $option === $option ) {

            foreach( $option as $_option => $_value ) {

                $this -> setValue( $_option, $_value );
            }

        } else {

            if( $this -> accept( $option ) ) {

                if( $value !== NULL ) {

                    $this -> values[ $option ] = $value;
                }
            }
        }
    }
}
