<?php

/**
 * HTTP Response Header Field Class: Proxy-Authenticate | HTTP\Headers\Response\Proxy-Authenticate.php
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
 * Response Header Field Validation Class: 'Proxy-Authenticate'
 */
use Next\Validation\HTTP\Headers\Response\ProxyAuthenticate as Validator;

/**
 * Response Header Field: 'Proxy-Authenticate'
 *
 * @package    Next\HTTP
 *
 * @uses       Next\Validation\Validator
 *             Next\HTTP\Headers\Field
 *             Next\Validation\HTTP\Headers\ProxyAuthenticate
 */
class ProxyAuthenticate extends Field {

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
        return [ 'name' => 'Proxy-Authenticate', 'preserveWhitespace' => TRUE ];
    }
}
