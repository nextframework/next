<?php

namespace Next\HTTP\Headers\Fields\Response;

use Next\HTTP\Headers\Fields\Response;                     # Response Headers Interface
use Next\HTTP\Headers\Fields\AbstractField;                # Header Field Abstract Class
use Next\Validate\HTTP\Headers\Response\XXSSProtection;    # Response X-XSS-Protection Header Field Validator Class

/**
 * X-XSS-Protection Header Field Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class XXSSProtection extends AbstractField implements Response {

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
        return new XXSSProtection( array( 'value' => $value ) );
    }

    /**
     * Set Up Header Options
     *
     * @return array
     *  Header Field Validation Options
     */
    public function setOptions() {
        return array( 'name' => 'X-XSS-Protection' );
    }
}
