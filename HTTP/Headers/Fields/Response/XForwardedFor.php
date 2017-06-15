<?php

/**
 * HTTP Response Header Field Class: X-Forwarded-For | HTTP\Headers\Fields\Response\XForwardedFor.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
namespace Next\HTTP\Headers\Fields\Response;

use Next\HTTP\Headers\Fields\Response;         # Response Headers Interface
use Next\HTTP\Headers\Fields\AbstractField;    # Header Field Abstract Class

/**
 * Response 'X-forwarded-For' Header Field Validator Class
 */
use Next\Validate\HTTP\Headers\Response\XForwardedFor as Validator;

/**
 * Response 'X-Forwarded-For' Header Field Class
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
     * @return \Next\Validate\Validator
     *  Associated Validator
     */
    protected function getValidator( $value ) {
        return new Validator( array( 'value' => $value ) );
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
