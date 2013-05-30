<?php

namespace Next\HTTP\Headers\Fields\Entity;

use Next\HTTP\Headers\Fields\Entity;        # Entity Headers Interface
use Next\HTTP\Headers\Fields\AbstractField; # Header Field Abstract Class

/**
 * Allow Header Field Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class Allow extends AbstractField implements Entity {

    // Methods Overwritten

    /**
     * POST Check Routines
     *
     * Allowed methods should be uppercased
     *
     * @param string $data
     *   Data to manipulate after validation
     *
     * @return string Validated Data
     */
    protected function postCheck( $data ) {
        return strtoupper( $data );
    }

    // Abstract Methods Implementation

    /**
     * Get Header Field Validator
     *
     * @return Next\Validate\Validate
     *   Associated Validator
     */
    protected function getValidator() {
        return new \Next\Validate\HTTP\Headers\Entity\Allow;
    }

    /**
     * Set Up Header Options
     *
     * @return array
     *   Header Field Validation Options
     */
    public function setOptions() {
        return array( 'name' => 'Allow', 'acceptMultiples' => TRUE );
    }
}
