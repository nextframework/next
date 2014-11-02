<?php

namespace Next\HTTP\Headers\Fields\Request;

use Next\HTTP\Headers\Fields\Request;        # Request Headers Interface
use Next\HTTP\Headers\Fields\AbstractField;  # Header Field Abstract Class

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
     * @return Next\Validate\Validate
     *  Associated Validator
     */
    protected function getValidator() {
        return new \Next\Validate\HTTP\Headers\Request\IfUnmodifiedSince;
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
