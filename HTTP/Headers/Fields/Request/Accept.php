<?php

namespace Next\HTTP\Headers\Fields\Request;

use Next\HTTP\Headers\Fields\Request;             # Request Headers Interface
use Next\HTTP\Headers\Fields\AbstractField;       # Header Field Abstract Class
use Next\Validate\HTTP\Headers\Request\Accept;    # Request Accept Header Field Validator Class

/**
 * Accept Header Field Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Accept extends AbstractField implements Request {

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
        return new Accept( array( 'value' => $value ) );
    }

    /**
     * Set Up Header Options
     *
     * @return array
     *  Header Field Validation Options
     */
    public function setOptions() {
        return array( 'name' => 'Accept', 'acceptMultiples' => TRUE );
    }
}
