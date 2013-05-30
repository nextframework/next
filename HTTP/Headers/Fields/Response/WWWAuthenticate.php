<?php

namespace Next\HTTP\Headers\Fields\Response;

use Next\HTTP\Headers\Fields\Response;         # Response Headers Interface
use Next\HTTP\Headers\Fields\AbstractField;    # Header Field Abstract Class

/**
 * WWW-Authenticate Header Field Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class WWWAuthenticate extends AbstractField implements Response {

    // Abstract Methods Implementation

    /**
     * Get Header Field Validator
     *
     * @return Next\Validate\Validate
     *   Associated Validator
     */
    protected function getValidator() {
        return new \Next\Validate\HTTP\Headers\Response\WWWAuthenticate;
    }

    /**
     * Set Up Header Options
     *
     * @return array
     *   Header Field Validation Options
     */
    public function setOptions() {
        return array( 'name' => 'WWW-Authenticate', 'preserveWhitespace' => TRUE );
    }
}
