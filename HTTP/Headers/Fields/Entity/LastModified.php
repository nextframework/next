<?php

namespace Next\HTTP\Headers\Fields\Entity;

use Next\HTTP\Headers\Fields\Entity;                   # Entity Headers Interface
use Next\HTTP\Headers\Fields\AbstractField;            # Header Field Abstract Class
use Next\Validate\HTTP\Headers\Entity\LastModified;    # Last-Modified Header Field Validator Class

/**
 * Last-Modified Header Field Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class LastModified extends AbstractField implements Entity {

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
        return new LastModified( array( 'value' => $value ) );
    }

    /**
     * Set Up Header Options
     *
     * @return array
     *  Header Field Validation Options
     */
    public function setOptions() {
        return array( 'name' => 'Last-Modified', 'preserveWhitespace' => TRUE );
    }
}
