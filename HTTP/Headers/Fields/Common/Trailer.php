<?php

namespace Next\HTTP\Headers\Fields\Common;

use Next\HTTP\Headers\Fields\AbstractField;       # Header Field Abstract Class
use Next\Validate\HTTP\Headers\Common\Trailer;    # Trailer Header Field Validator Class

/**
 * Trailer Header Field Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Trailer extends AbstractField {

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
        return new Trailer( array( 'value' => $value ) );
    }

    /**
     * Set Up Header Options
     *
     * @return array
     *  Header Field Validation Options
     */
    public function setOptions() {
        return array( 'name' => 'Trailer' );
    }
}
