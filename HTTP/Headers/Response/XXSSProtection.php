<?php

/**
 * HTTP Response Header Field Class: X-XSS-Protection | HTTP\Headers\Response\XXSSProtection.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Headers\Response;

use Next\HTTP\Headers\Field;    # Header Field Abstract Class

/**
 * Response 'X-XSS-Protection' Header Field Validator Class
 */
use Next\Validation\HTTP\Headers\Response\XXSSProtection as Validator;

/**
 * Response 'X-XSS-Protection' Header Field Class
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
class XXSSProtection extends Field {

    // Abstract Methods Implementation

    /**
     * Get Header Field Validator
     *
     * @param mixed|string $value
     *  Header value to be validated
     *
     * @return \Next\Validation\Validator
     *  Associated Validator
     */
    protected function getValidator( $value ) {
        return new Validator( [ 'value' => $value ] );
    }

    /**
     * Set Up Header Options
     *
     * @return array
     *  Header Field Validation Options
     */
    public function setOptions() {
        return [ 'name' => 'X-XSS-Protection' ];
    }
}
