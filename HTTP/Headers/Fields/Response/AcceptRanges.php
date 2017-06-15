<?php

namespace Next\HTTP\Headers\Fields\Response;

use Next\HTTP\Headers\Fields\Response;                   # Response Headers Interface
use Next\HTTP\Headers\Fields\AbstractField;              # Header Field Abstract Class
use Next\Validate\HTTP\Headers\Response\AcceptRanges;    # Response Accept-Ranges Header Field Validator Class

/**
 * Accept-Ranges Header Field Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class AcceptRanges extends AbstractField implements Response {

    // Methods Overwritten

    /**
     * POST Check Routines
     *
     * Accept Ranges must be lowercased
     *
     * @param string $data
     *  Data to manipulate after validation
     *
     * @return string Validated Data
     */
    protected function postCheck( $data ) {
        return strtolower( $data );
    }

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
        return new AcceptRanges( array( 'value' => $value ) );
    }

    /**
     * Set Up Header Options
     *
     * @return array
     *  Header Field Validation Options
     */
    public function setOptions() {
        return array( 'name' => 'Accept-Ranges' );
    }
}
