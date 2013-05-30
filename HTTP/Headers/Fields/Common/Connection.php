<?php

namespace Next\HTTP\Headers\Fields\Common;

use Next\HTTP\Headers\Fields\AbstractField; # Header Field Abstract Class

/**
 * Connection Header Field Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Connection extends AbstractField {

    // Methods Overwritten

    /**
     * POST Check Routines
     *
     * Connection types must be lowercased
     *
     * @param string $data
     *   Data to manipulate after validation
     *
     * @return string
     *   Data to be validated
     */
    protected function postCheck( $data ) {
        return strtolower( $data );
    }

    // Abstract Methods Implementation

    /**
     * Get Header Field Validator
     *
     * @return Next\Validate\Validate
     *   Associated Validator
     */
    protected function getValidator() {
        return new \Next\Validate\HTTP\Headers\Common\Connection;
    }

    /**
     * Set Up Header Options
     *
     * @return array
     *   Header Field Validation Options
     */
    public function setOptions() {
        return array( 'name' => 'Connection' );
    }
}
