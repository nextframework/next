<?php

namespace Next\HTTP\Headers\Fields;

use Next\Validate\HTTP\Headers\Generic;    # Generic Header Field Validator Class

/**
 * Generic Header Field Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Generic extends AbstractField {

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
        return new Generic( array( 'value' => $value ) );
    }

    /**
     * Set Up Header Options
     *
     * @return array
     *  Header Field Validation Options
     */
    public function setOptions() {
        return array( 'name' => 'Generic', 'acceptMultiples' => TRUE, 'preserveWhitespace' => TRUE );
    }
}
