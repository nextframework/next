<?php

namespace Next\HTTP\Headers\Fields\Response;

use Next\HTTP\Headers\Fields\Response;                    # Response Headers Interface
use Next\HTTP\Headers\Fields\AbstractField;               # Header Field Abstract Class
use Next\Validate\HTTP\Headers\Response\XForwardedFor;    # X-forwarded-For Header Field Validator Class

/**
 * X-Forwarded-For Header Field Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class XForwardedFor extends AbstractField implements Response {

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
        return new XForwardedFor( array( 'value' => $value ) );
    }

    /**
     * Set Up Header Options
     *
     * @return array
     *  Header Field Validation Options
     */
    public function setOptions() {
        return array( 'name' => 'X-Forwarded-For', 'acceptMultiples' => TRUE );
    }
}
