<?php

namespace Next\HTTP\Headers\Fields\Request;

use Next\HTTP\Headers\Fields\Request;                        # Request Headers Interface
use Next\HTTP\Headers\Fields\AbstractField;                  # Header Field Abstract Class
use Next\Validate\HTTP\Headers\Request\IfUnmodifiedSince;    # Request If-Unmodified-Since Header Field Validator Class

/**
 * If-Unmodified-Since Header Field Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class IfUnmodifiedSince extends AbstractField implements Request {

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
        return new IfUnmodifiedSince( array( 'value' => $value ) );
    }

    /**
     * Set Up Header Options
     *
     * @return array
     *  Header Field Validation Options
     */
    public function setOptions() {
        return array( 'name' => 'If-Unmodified-Since', 'preserveWhitespace' => TRUE );
    }
}
