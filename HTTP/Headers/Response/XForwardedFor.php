<?php

/**
 * HTTP Response Header Field Class: X-Forwarded-For | HTTP\Headers\Response\XForwardedFor.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\HTTP\Headers\Response;

use Next\Validation\Validator as Validators;    # Validators Interface
use Next\HTTP\Headers\Field;                    # Header Field Abstract Class

/**
 * Response Header Field Validation Class: 'X-forwarded-For'
 */
use Next\Validation\HTTP\Headers\Response\XForwardedFor as Validator;

/**
 * Response Header Field: 'X-Forwarded-For'
 *
 * @package    Next\HTTP
 *
 * @uses       Next\Validation\Validator
 *             Next\HTTP\Headers\Field
 *             Next\Validation\HTTP\Headers\XForwardedFor
 */
class XForwardedFor extends Field {

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
    protected function getValidator( $value ) : Validators {
        return new Validator( [ 'value' => $value ] );
    }

    /**
     * Set Up Header Options
     *
     * @return array
     *  Header Field Validation Options
     */
    public function setOptions() : array {
        return [ 'name' => 'X-Forwarded-For', 'acceptMultiples' => TRUE ];
    }
}
