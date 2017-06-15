<?php

namespace Next\HTTP\Headers\Fields\Common;

use Next\HTTP\Headers\Fields\AbstractField;       # Header Field Abstract Class
use Next\Validate\HTTP\Headers\Common\Warning;    # Warning Header Field Validator Class

/**
 * Warning Header Field Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Warning extends AbstractField {

    // Methods Overwritten

    /**
     * PRE Check Routines
     *
     * Distinguishing Warnng Message from Warning Date
     *
     * @param string $data
     *  Data to manipulate before validation
     *
     * @return string Data to Validate
     */
    protected function preCheck( $data ) {

        /**
         * @internal
         * Warning Date and Warning Message are too generic at same time they both
         * are optional, so let's give some help to our validator with something
         * to distinguish them
         */
        return preg_replace( '/\s*(Mon|Tue|Wed|Thu|Fri|Sat|Sun)/', '#\\1', $data );
    }

    /**
     * POST Check Routines
     *
     * Removing distinguishing character
     *
     * @param string $data
     *  Data to manipulate after validation
     *
     * @return string Validated Data
     */
    protected function postCheck( $data ) {
        return preg_replace( '/#(\w{3}),/', ' \\1,', $data );
    }

    // Abstract Methods Implementation

    /**
     * Get Header Field Validator
     *
     * @param mixed|string $value
     *  Header value to be validated
     *
     * @return Next\Validate\Validator
     *  Associated Validator
     */
    protected function getValidator( $value ) {
        return new Warning( array( 'value' => $value ) );
    }

    /**
     * Set Up Header Options
     *
     * @return array
     *  Header Field Validation Options
     */
    public function setOptions() {

        return array( 'name' => 'Warning', 'preserveWhitespace' => TRUE );
    }
}
