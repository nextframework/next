<?php

namespace Next\HTTP\Stream\Context\Options;

use Next\Components\Object;    # Object Class

/**
 * Abstract Stream Context Options Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
abstract class AbstractOptions extends Object implements Option {

    /**
     * Context Options Values
     *
     * @var array $values
     */
    private $values = array();

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

        if( is_array( $option ) ) {

            foreach( $option as $_option => $_value ) {

                $this -> setValue( $_option, $_value );
            }

        } else {

            if( $this -> accept( $option ) ) {

                if( ! is_null( $value ) ) {

                    $this -> values[ $option ] = $value;
                }
            }
        }
    }
}
